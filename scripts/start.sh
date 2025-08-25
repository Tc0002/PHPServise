#!/bin/bash

# Laravel Dockerコンテナの初回起動とセットアップスクリプト

echo "🚀 Laravel Docker環境の初回起動を開始します..."
echo ""

# 既存のコンテナを停止・削除
echo "🧹 既存のコンテナをクリーンアップ..."
docker-compose down -v

# イメージをビルド
echo "🔨 Dockerイメージをビルド中..."
docker-compose build --no-cache

# コンテナを起動
echo "📦 コンテナを起動中..."
docker-compose up -d

# 起動完了まで待機
echo "⏳ コンテナの起動完了を待機中..."
sleep 10

# コンテナの状態確認
echo "📋 コンテナの状態:"
docker-compose ps

echo ""
echo "🔑 アプリケーションキーを生成中..."
docker-compose exec app php artisan key:generate

echo ""
echo "🗄️ データベースのマイグレーションを実行中..."
docker-compose exec app php artisan migrate

echo ""
echo "📁 ストレージディレクトリの権限を設定中..."
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache

echo ""
echo "✅ セットアップ完了！"
echo ""
echo "🌐 アクセス方法:"
echo "   - Laravelアプリ: http://localhost:8000"
echo "   - phpMyAdmin: http://localhost:8080"
echo "     (ユーザー: laravel, パスワード: secret)"
echo ""
echo "💡 便利なコマンド:"
echo "   - ./scripts/attach.sh    # コンテナにアタッチ"
echo "   - ./scripts/restart.sh   # コンテナ再起動"
echo "   - ./scripts/stop.sh      # コンテナ停止"
echo ""
echo "📊 ログの確認: docker-compose logs -f" 