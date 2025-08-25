#!/bin/bash

# MinIO初期化スクリプト
# 使用方法: ./init-minio.sh

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

echo "🐳 MinIO初期化スクリプトを開始します..."
echo ""

# 環境変数ファイルの確認
if [[ ! -f ".env" ]]; then
    print_error ".envファイルが見つかりません"
    print_info "先に ./deploy-stack.sh を実行してください"
    exit 1
fi

# 環境変数の読み込み
source .env

# MinIOの状態確認
print_info "MinIOの状態確認中..."
if ! docker service ls | grep -q "minio"; then
    print_error "MinIOサービスが起動していません"
    print_info "先にスタックをデプロイしてください"
    exit 1
fi

# MinIOクライアントのインストール
print_info "MinIOクライアントをインストール中..."
if ! command -v mc &> /dev/null; then
    print_info "MinIOクライアントをダウンロード中..."
    wget https://dl.min.io/client/mc/release/linux-amd64/mc
    chmod +x mc
    sudo mv mc /usr/local/bin/
    print_success "MinIOクライアントのインストール完了"
else
    print_success "MinIOクライアントは既にインストール済み"
fi

# MinIOサーバーの起動完了まで待機
print_info "MinIOサーバーの起動完了を待機中..."
sleep 30

# MinIOサーバーへの接続設定
print_info "MinIOサーバーへの接続設定中..."
mc alias set local http://localhost:9000 ${MINIO_ROOT_USER:-minioadmin} ${MINIO_ROOT_PASSWORD:-minioadmin}

# バケットの作成
print_info "バケットを作成中..."
BUCKET_NAME=${S3_BUCKET:-laravel-product-system}

if mc ls local | grep -q "$BUCKET_NAME"; then
    print_info "バケット '$BUCKET_NAME' は既に存在します"
else
    mc mb local/$BUCKET_NAME
    print_success "バケット '$BUCKET_NAME' を作成しました"
fi

# デフォルトフォルダ構造の作成
print_info "デフォルトフォルダ構造を作成中..."
FOLDERS=("uploads/products" "uploads/documents" "uploads/temp" "backups" "exports")

for folder in "${FOLDERS[@]}"; do
    if mc ls local/$BUCKET_NAME | grep -q "$folder"; then
        print_info "フォルダ '$folder' は既に存在します"
    else
        mc cp /dev/null local/$BUCKET_NAME/$folder/.keep
        print_success "フォルダ '$folder' を作成しました"
    fi
done

# バケットポリシーの設定
print_info "バケットポリシーを設定中..."
cat > /tmp/bucket-policy.json << EOF
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Principal": {
                "AWS": "*"
            },
            "Action": [
                "s3:GetBucketLocation",
                "s3:ListBucket"
            ],
            "Resource": "arn:aws:s3:::$BUCKET_NAME"
        },
        {
            "Effect": "Allow",
            "Principal": {
                "AWS": "*"
            },
            "Action": [
                "s3:GetObject"
            ],
            "Resource": "arn:aws:s3:::$BUCKET_NAME/*"
        }
    ]
}
EOF

mc policy set local/$BUCKET_NAME /tmp/bucket-policy.json
print_success "バケットポリシーを設定しました"

# 一時ファイルの削除
rm -f /tmp/bucket-policy.json

# 設定の確認
print_info "設定の確認中..."
echo ""
print_info "MinIO設定情報:"
echo "  - エンドポイント: http://localhost:9000"
echo "  - Web UI: http://localhost:9001"
echo "  - ユーザー: ${MINIO_ROOT_USER:-minioadmin}"
echo "  - パスワード: ${MINIO_ROOT_PASSWORD:-minioadmin}"
echo "  - バケット: $BUCKET_NAME"
echo ""

print_info "バケット内容:"
mc ls local/$BUCKET_NAME --recursive

echo ""
print_success "🎉 MinIO初期化が完了しました！"
echo ""
print_info "次のステップ:"
echo "1. MinIO Web UI (http://localhost:9001) でログイン"
echo "2. バケットとフォルダ構造の確認"
echo "3. Laravelアプリケーションでのファイルアップロードテスト"
echo "4. ファイル検索機能のテスト"
echo ""
print_info "便利なコマンド:"
echo "  - ファイル一覧: mc ls local/$BUCKET_NAME"
echo "  - ファイルアップロード: mc cp file.txt local/$BUCKET_NAME/uploads/"
echo "  - ファイルダウンロード: mc cp local/$BUCKET_NAME/uploads/file.txt ."
echo "  - フォルダ作成: mc mb local/$BUCKET_NAME/new-folder" 