#!/bin/bash

# Docker Swarmスタックデプロイスクリプト
# 使用方法: ./deploy-stack.sh [stack_name]

set -e

# 色付き出力用の関数
print_info() {
    echo -e "\033[1;34m[INFO]\033[0m $1"
}

print_success() {
    echo -e "\033[1;32m[SUCCESS]\033[0m $1"
}

print_error() {
    echo -e "\033[1;31m[ERROR]\033[0m $1"
}

print_warning() {
    echo -e "\033[1;33m[WARNING]\033[0m $1"
}

# 引数の確認
if [[ $# -lt 1 ]]; then
    print_error "使用方法: $0 [stack_name]"
    print_info "例: $0 laravel-system"
    exit 1
fi

STACK_NAME=$1

echo "🚀 Docker Swarmスタックのデプロイを開始します..."
echo "スタック名: $STACK_NAME"
echo ""

# Docker Swarmの状態確認
if ! docker info | grep -q "Swarm: active"; then
    print_error "Docker Swarmがアクティブではありません"
    print_info "先に ./setup-swarm.sh manager を実行してください"
    exit 1
fi

# ノードの状態確認
print_info "ノードの状態確認中..."
NODE_COUNT=$(docker node ls --format "{{.Status}}" | grep -c "Ready")
if [[ $NODE_COUNT -lt 2 ]]; then
    print_warning "ノード数が少ないです（現在: $NODE_COUNT）"
    print_info "本番環境では3つ以上のノードを推奨します"
fi

docker node ls
echo ""

# 環境変数ファイルの確認
if [[ ! -f ".env" ]]; then
    print_warning ".envファイルが見つかりません"
    print_info "環境変数の設定が必要です"
    
    if [[ -f "env.example" ]]; then
        print_info "env.exampleから.envファイルを作成します"
        cp env.example .env
        print_success ".envファイルを作成しました"
    else
        print_error "env.exampleファイルも見つかりません"
        exit 1
    fi
fi

# 環境変数の読み込み
print_info "環境変数を読み込み中..."
source .env

# 必要な環境変数の確認
REQUIRED_VARS=("MINIO_ROOT_USER" "MINIO_ROOT_PASSWORD" "S3_BUCKET")
for var in "${REQUIRED_VARS[@]}"; do
    if [[ -z "${!var}" ]]; then
        print_error "必要な環境変数が設定されていません: $var"
        exit 1
    fi
done

print_success "環境変数の確認完了"
echo ""

# 既存スタックの確認
if docker stack ls | grep -q "$STACK_NAME"; then
    print_warning "スタック '$STACK_NAME' は既に存在します"
    read -p "既存のスタックを削除して再デプロイしますか？ (y/N): " response
    
    if [[ "$response" =~ ^[Yy]$ ]]; then
        print_info "既存スタックを削除中..."
        docker stack rm "$STACK_NAME"
        
        # 削除完了まで待機
        print_info "スタック削除完了を待機中..."
        while docker stack ls | grep -q "$STACK_NAME"; do
            sleep 5
        done
        print_success "既存スタックの削除完了"
    else
        print_info "デプロイをキャンセルしました"
        exit 0
    fi
fi

# ネットワークの確認
print_info "ネットワークの確認中..."
if ! docker network ls | grep -q "traefik-public"; then
    print_info "traefik-publicネットワークを作成中..."
    docker network create --driver=overlay --attachable traefik-public
    print_success "ネットワーク作成完了"
fi

# イメージのビルド
print_info "Dockerイメージをビルド中..."
print_warning "初回ビルドには時間がかかります（10-30分程度）"

# Laravelアプリケーションイメージ
print_info "Laravelアプリケーションイメージをビルド中..."
docker build -t laravel-app:latest ../app

# ファイル処理サービスイメージ
print_info "ファイル処理サービスイメージをビルド中..."
docker build -t file-processor:latest ../services/file-processor

# 検索インデックスサービスイメージ
print_info "検索インデックスサービスイメージをビルド中..."
docker build -t search-indexer:latest ../services/search-indexer

print_success "Dockerイメージビルド完了"
echo ""

# スタックのデプロイ
print_info "スタック '$STACK_NAME' をデプロイ中..."
docker stack deploy -c docker-compose.yml "$STACK_NAME"

# デプロイ完了まで待機
print_info "デプロイ完了を待機中..."
sleep 10

# サービスの状態確認
print_info "サービスの状態確認中..."
docker stack services "$STACK_NAME"

echo ""
print_info "各サービスの詳細状態:"
docker stack ps "$STACK_NAME"

echo ""
print_success "🎉 スタック '$STACK_NAME' のデプロイが完了しました！"
echo ""
print_info "アクセス先:"
echo "  - Traefik Dashboard: http://traefik.localhost:8080"
echo "  - Consul UI: http://consul.localhost:8500"
echo "  - Laravel App: http://laravel.localhost:8000"
echo "  - pgAdmin: http://pgadmin.localhost:8080"
echo "  - MinIO Console: http://minio.localhost:9001"
echo "    (ユーザー: ${MINIO_ROOT_USER:-minioadmin}, パスワード: ${MINIO_ROOT_PASSWORD:-minioadmin})"
echo ""
print_info "管理コマンド:"
echo "  - サービス確認: docker stack services $STACK_NAME"
echo "  - ログ確認: docker service logs ${STACK_NAME}_laravel-app"
echo "  - スケール変更: docker service scale ${STACK_NAME}_laravel-app=3"
echo "  - スタック削除: docker stack rm $STACK_NAME"
echo ""
print_info "ヘルスチェック:"
echo "  - ファイル処理サービス: curl http://localhost:8000/health"
echo "  - 検索インデックスサービス: curl http://localhost:8000/health"
echo "  - MinIO: curl http://localhost:9000/minio/health/live"
echo ""
print_info "次のステップ:"
echo "1. 各サービスの起動完了を確認"
echo "2. ヘルスチェックの実行"
echo "3. MinIOコンソールでバケットの確認"
echo "4. アプリケーションの動作確認"
echo "5. 必要に応じてスケーリング調整" 