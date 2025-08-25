#!/bin/bash

# Laravel Dockerコンテナ内部にアタッチするスクリプト
# 使用方法: ./scripts/attach.sh [container_name]

# デフォルトのコンテナ名
DEFAULT_CONTAINER="laravel_app"

# 引数が指定されている場合はそれを使用、そうでなければデフォルト
CONTAINER_NAME=${1:-$DEFAULT_CONTAINER}

echo "🐳 Dockerコンテナ '$CONTAINER_NAME' にアタッチします..."
echo "📋 利用可能なコンテナ:"
docker-compose ps

echo ""
echo "🔗 アタッチ中... (終了するには 'exit' または Ctrl+D)"
echo "💡 便利なコマンド:"
echo "   - php artisan migrate    # マイグレーション実行"
echo "   - composer install       # 依存関係インストール"
echo "   - php artisan tinker     # Tinker起動"
echo ""

# コンテナが起動しているかチェック
if ! docker-compose ps | grep -q "$CONTAINER_NAME.*Up"; then
    echo "❌ エラー: コンテナ '$CONTAINER_NAME' が起動していません"
    echo "💡 先に './scripts/start.sh' を実行してください"
    exit 1
fi

# コンテナにアタッチ
docker-compose exec "$CONTAINER_NAME" bash 