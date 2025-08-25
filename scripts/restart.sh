#!/bin/bash

# Laravel Dockerコンテナの再起動スクリプト

echo "🔄 Laravel Docker環境を再起動します..."
echo ""

# 現在の状態確認
echo "📋 現在のコンテナ状態:"
docker-compose ps

echo ""
echo "⏹️ コンテナを停止中..."
docker-compose down

echo ""
echo "📦 コンテナを再起動中..."
docker-compose up -d

# 起動完了まで待機
echo "⏳ コンテナの起動完了を待機中..."
sleep 5

echo ""
echo "📋 再起動後のコンテナ状態:"
docker-compose ps

echo ""
echo "✅ 再起動完了！"
echo ""
echo "🌐 アクセス方法:"
echo "   - Laravelアプリ: http://localhost:8000"
echo "   - phpMyAdmin: http://localhost:8080"
echo ""
echo "💡 便利なコマンド:"
echo "   - ./scripts/attach.sh    # コンテナにアタッチ"
echo "   - ./scripts/stop.sh      # コンテナ停止"
echo "   - ./scripts/logs.sh      # ログ確認" 