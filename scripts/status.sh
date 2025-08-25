#!/bin/bash

# Laravel Dockerコンテナの状態確認スクリプト

echo "📊 Laravel Docker環境の状態を確認します..."
echo ""

# コンテナの状態
echo "🐳 コンテナの状態:"
docker-compose ps

echo ""

# イメージの状態
echo "🖼️ Dockerイメージ:"
docker images | grep -E "(laravel|nginx|mysql|phpmyadmin)"

echo ""

# ボリュームの状態
echo "💾 Dockerボリューム:"
docker volume ls | grep laravel

echo ""

# ネットワークの状態
echo "🌐 Dockerネットワーク:"
docker network ls | grep laravel

echo ""

# リソース使用量
echo "💻 リソース使用量:"
echo "CPU使用率:"
docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}"

echo ""

# ポート使用状況
echo "🔌 ポート使用状況:"
echo "8000 (Laravel): $(lsof -i :8000 2>/dev/null | wc -l) 接続"
echo "3306 (MySQL): $(lsof -i :3306 2>/dev/null | wc -l) 接続"
echo "8080 (phpMyAdmin): $(lsof -i :8080 2>/dev/null | wc -l) 接続"

echo ""
echo "✅ 状態確認完了！"
echo ""
echo "💡 便利なコマンド:"
echo "   - ./scripts/attach.sh    # コンテナにアタッチ"
echo "   - ./scripts/logs.sh      # ログ確認"
echo "   - ./scripts/restart.sh   # 再起動" 