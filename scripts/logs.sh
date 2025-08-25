#!/bin/bash

# Laravel Dockerコンテナのログ確認スクリプト

echo "📊 Dockerコンテナのログを表示します..."
echo ""

# 利用可能なコンテナの表示
echo "📋 利用可能なコンテナ:"
docker-compose ps

echo ""
echo "🔍 ログの表示方法を選択してください:"
echo "1) 全コンテナのログ (リアルタイム)"
echo "2) 特定のコンテナのログ"
echo "3) Laravelアプリケーションのログ"
echo "4) データベースのログ"
echo "5) Nginxのログ"
echo ""

read -p "選択してください (1-5): " choice

case $choice in
    1)
        echo "📊 全コンテナのログを表示中... (終了: Ctrl+C)"
        docker-compose logs -f
        ;;
    2)
        echo "📋 利用可能なコンテナ:"
        docker-compose ps --format "table {{.Name}}\t{{.Status}}"
        echo ""
        read -p "コンテナ名を入力してください: " container_name
        if docker-compose ps | grep -q "$container_name"; then
            echo "📊 $container_name のログを表示中... (終了: Ctrl+C)"
            docker-compose logs -f "$container_name"
        else
            echo "❌ エラー: コンテナ '$container_name' が見つかりません"
        fi
        ;;
    3)
        echo "📊 Laravelアプリケーションのログを表示中... (終了: Ctrl+C)"
        docker-compose logs -f app
        ;;
    4)
        echo "📊 データベースのログを表示中... (終了: Ctrl+C)"
        docker-compose logs -f db
        ;;
    5)
        echo "📊 Nginxのログを表示中... (終了: Ctrl+C)"
        docker-compose logs -f nginx
        ;;
    *)
        echo "❌ 無効な選択です"
        exit 1
        ;;
esac 