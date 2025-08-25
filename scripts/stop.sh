#!/bin/bash

# Laravel Dockerコンテナの停止スクリプト

echo "⏹️ Laravel Docker環境を停止します..."
echo ""

# 現在の状態確認
echo "📋 現在のコンテナ状態:"
docker-compose ps

echo ""
echo "🛑 コンテナを停止中..."
docker-compose down

echo ""
echo "🧹 ボリュームも削除しますか？ (y/N)"
read -r response

if [[ "$response" =~ ^[Yy]$ ]]; then
    echo "🗑️ ボリュームを削除中..."
    docker-compose down -v
    echo "✅ ボリュームも削除されました"
else
    echo "💾 ボリュームは保持されました"
fi

echo ""
echo "✅ 停止完了！"
echo ""
echo "💡 再起動する場合:"
echo "   - ./scripts/start.sh     # 初回起動（クリーンインストール）"
echo "   - ./scripts/restart.sh   # 通常の再起動"
echo ""
echo "📊 残存コンテナの確認: docker ps -a" 