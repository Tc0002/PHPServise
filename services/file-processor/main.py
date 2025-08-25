#!/usr/bin/env python3
"""
File Processor Service
AWS S3との統合とファイル処理を行うマイクロサービス
"""

import os
import logging
from typing import List, Optional
from fastapi import FastAPI, HTTPException, UploadFile, File, Form
from fastapi.responses import JSONResponse
import boto3
from elasticsearch import Elasticsearch
import redis
from pydantic import BaseModel
import json
from datetime import datetime
import magic

# ログ設定
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# FastAPIアプリケーション
app = FastAPI(title="File Processor Service", version="1.0.0")

# AWS S3設定（MinIO対応）
s3_client = boto3.client(
    's3',
    aws_access_key_id=os.getenv('AWS_ACCESS_KEY_ID'),
    aws_secret_access_key=os.getenv('AWS_SECRET_ACCESS_KEY'),
    region_name=os.getenv('AWS_DEFAULT_REGION', 'us-east-1'),
    endpoint_url=os.getenv('AWS_ENDPOINT'),  # MinIOエンドポイント
    use_ssl=False,  # ローカル環境ではSSL無効
    verify=False    # 証明書検証無効
)

# Elasticsearch設定
es_client = Elasticsearch(
    [f"{os.getenv('ELASTICSEARCH_HOST', 'elasticsearch')}:{os.getenv('ELASTICSEARCH_PORT', '9200')}"]
)

# Redis設定
redis_client = redis.Redis(
    host=os.getenv('REDIS_HOST', 'redis'),
    port=int(os.getenv('REDIS_PORT', '6379')),
    decode_responses=True
)

# S3バケット名
S3_BUCKET = os.getenv('S3_BUCKET', 'laravel-product-system')

# MinIO初期化処理
def initialize_minio():
    """MinIOの初期化処理"""
    try:
        # バケットの存在確認
        s3_client.head_bucket(Bucket=S3_BUCKET)
        logger.info(f"バケット '{S3_BUCKET}' は既に存在します")
    except Exception as e:
        # バケットが存在しない場合は作成
        try:
            s3_client.create_bucket(Bucket=S3_BUCKET)
            logger.info(f"バケット '{S3_BUCKET}' を作成しました")
            
            # デフォルトのフォルダ構造を作成
            default_folders = ['uploads/products', 'uploads/documents', 'uploads/temp', 'backups', 'exports']
            for folder in default_folders:
                s3_client.put_object(Bucket=S3_BUCKET, Key=f"{folder}/.keep", Body="")
            
            logger.info("デフォルトフォルダ構造を作成しました")
        except Exception as create_error:
            logger.error(f"バケット作成エラー: {create_error}")
            raise

# アプリケーション起動時の初期化
@app.on_event("startup")
async def startup_event():
    """アプリケーション起動時の処理"""
    try:
        initialize_minio()
        logger.info("MinIO初期化完了")
    except Exception as e:
        logger.error(f"MinIO初期化エラー: {e}")
        raise

# データモデル
class FileInfo(BaseModel):
    file_name: str
    file_size: int
    file_type: str
    s3_key: str
    uploaded_at: datetime
    metadata: dict

class SearchQuery(BaseModel):
    query: str
    file_type: Optional[str] = None
    size: Optional[int] = 10
    from_: Optional[int] = 0

@app.get("/health")
async def health_check():
    """ヘルスチェックエンドポイント"""
    try:
        # S3接続確認
        s3_client.head_bucket(Bucket=S3_BUCKET)
        
        # Elasticsearch接続確認
        es_client.ping()
        
        # Redis接続確認
        redis_client.ping()
        
        return {"status": "healthy", "timestamp": datetime.now().isoformat()}
    except Exception as e:
        logger.error(f"Health check failed: {e}")
        raise HTTPException(status_code=503, detail="Service unhealthy")

@app.post("/upload")
async def upload_file(
    file: UploadFile = File(...),
    category: str = Form("general"),
    tags: str = Form("")
):
    """ファイルアップロード処理"""
    try:
        # ファイル情報の取得
        file_content = await file.read()
        file_size = len(file_content)
        
        # ファイルタイプの判定
        file_type = magic.from_buffer(file_content, mime=True)
        
        # S3キーの生成
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        s3_key = f"uploads/{category}/{timestamp}_{file.filename}"
        
        # S3へのアップロード
        s3_client.put_object(
            Bucket=S3_BUCKET,
            Key=s3_key,
            Body=file_content,
            ContentType=file_type,
            Metadata={
                'original_name': file.filename,
                'category': category,
                'tags': tags,
                'uploaded_at': datetime.now().isoformat()
            }
        )
        
        # Elasticsearchへのインデックス
        file_info = {
            'file_name': file.filename,
            'file_size': file_size,
            'file_type': file_type,
            's3_key': s3_key,
            'uploaded_at': datetime.now().isoformat(),
            'category': category,
            'tags': tags.split(',') if tags else [],
            'metadata': {
                'original_name': file.filename,
                'category': category,
                'tags': tags.split(',') if tags else []
            }
        }
        
        es_client.index(
            index='files',
            document=file_info
        )
        
        # Redisキャッシュの更新
        cache_key = f"file:{s3_key}"
        redis_client.setex(cache_key, 3600, json.dumps(file_info))
        
        logger.info(f"File uploaded successfully: {s3_key}")
        
        return {
            "status": "success",
            "message": "File uploaded successfully",
            "file_info": file_info
        }
        
    except Exception as e:
        logger.error(f"File upload failed: {e}")
        raise HTTPException(status_code=500, detail=f"Upload failed: {str(e)}")

@app.get("/files")
async def list_files(prefix: str = "", limit: int = 100):
    """ファイル一覧取得"""
    try:
        # S3からファイル一覧を取得
        response = s3_client.list_objects_v2(
            Bucket=S3_BUCKET,
            Prefix=prefix,
            MaxKeys=limit
        )
        
        files = []
        if 'Contents' in response:
            for obj in response['Contents']:
                # Redisキャッシュからファイル情報を取得
                cache_key = f"file:{obj['Key']}"
                cached_info = redis_client.get(cache_key)
                
                if cached_info:
                    files.append(json.loads(cached_info))
                else:
                    # キャッシュにない場合はS3から取得
                    file_info = {
                        'file_name': obj['Key'].split('/')[-1],
                        'file_size': obj['Size'],
                        's3_key': obj['Key'],
                        'last_modified': obj['LastModified'].isoformat()
                    }
                    files.append(file_info)
        
        return {
            "status": "success",
            "files": files,
            "total": len(files)
        }
        
    except Exception as e:
        logger.error(f"File listing failed: {e}")
        raise HTTPException(status_code=500, detail=f"Listing failed: {str(e)}")

@app.post("/search")
async def search_files(search_query: SearchQuery):
    """ファイル検索"""
    try:
        # Elasticsearchでの検索クエリ構築
        query = {
            "query": {
                "bool": {
                    "must": [
                        {
                            "multi_match": {
                                "query": search_query.query,
                                "fields": ["file_name", "category", "tags"]
                            }
                        }
                    ]
                }
            },
            "highlight": {
                "fields": {
                    "file_name": {},
                    "category": {},
                    "tags": {}
                }
            },
            "sort": [
                {"_score": {"order": "desc"}},
                {"uploaded_at": {"order": "desc"}}
            ],
            "size": search_query.size,
            "from": search_query.from_
        }
        
        # ファイルタイプフィルター
        if search_query.file_type:
            query["query"]["bool"]["filter"] = [
                {"term": {"file_type": search_query.file_type}}
            ]
        
        # 検索実行
        response = es_client.search(
            index='files',
            body=query
        )
        
        # 結果の整形
        results = []
        for hit in response['hits']['hits']:
            result = hit['_source']
            result['score'] = hit['_score']
            if 'highlight' in hit:
                result['highlights'] = hit['highlight']
            results.append(result)
        
        return {
            "status": "success",
            "results": results,
            "total": response['hits']['total']['value'],
            "took": response['took']
        }
        
    except Exception as e:
        logger.error(f"File search failed: {e}")
        raise HTTPException(status_code=500, detail=f"Search failed: {str(e)}")

@app.delete("/files/{s3_key:path}")
async def delete_file(s3_key: str):
    """ファイル削除"""
    try:
        # S3からファイル削除
        s3_client.delete_object(Bucket=S3_BUCKET, Key=s3_key)
        
        # Elasticsearchからインデックス削除
        es_client.delete_by_query(
            index='files',
            body={"query": {"term": {"s3_key": s3_key}}}
        )
        
        # Redisキャッシュから削除
        cache_key = f"file:{s3_key}"
        redis_client.delete(cache_key)
        
        logger.info(f"File deleted successfully: {s3_key}")
        
        return {
            "status": "success",
            "message": "File deleted successfully"
        }
        
    except Exception as e:
        logger.error(f"File deletion failed: {e}")
        raise HTTPException(status_code=500, detail=f"Deletion failed: {str(e)}")

@app.get("/stats")
async def get_stats():
    """統計情報取得"""
    try:
        # S3バケットの統計
        bucket_stats = s3_client.get_bucket_analytics_configuration(
            Bucket=S3_BUCKET,
            Id='EntireBucket'
        )
        
        # Elasticsearchの統計
        es_stats = es_client.indices.stats(index='files')
        
        # Redisの統計
        redis_info = redis_client.info()
        
        return {
            "status": "success",
            "s3": {
                "bucket": S3_BUCKET,
                "region": os.getenv('AWS_DEFAULT_REGION', 'us-east-1')
            },
            "elasticsearch": {
                "index_count": len(es_stats['indices']),
                "total_docs": es_stats['_all']['total']['docs']['count']
            },
            "redis": {
                "connected_clients": redis_info.get('connected_clients', 0),
                "used_memory": redis_info.get('used_memory_human', '0B')
            }
        }
        
    except Exception as e:
        logger.error(f"Stats retrieval failed: {e}")
        raise HTTPException(status_code=500, detail=f"Stats failed: {str(e)}")

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000) 