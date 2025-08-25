#!/bin/bash

# Laravel Docker環境のヘルプスクリプト

echo "🐳 Laravel Docker環境 - ショートカットコマンド一覧"
echo "=================================================="
echo ""

echo "🚀 起動・停止系:"
echo "  ./scripts/start.sh     - 初回起動（クリーンインストール）"
echo "  ./scripts/restart.sh   - コンテナ再起動"
echo "  ./scripts/stop.sh      - コンテナ停止"
echo ""

echo "🔍 監視・確認系:"
echo "  ./scripts/status.sh    - 環境の状態確認"
echo "  ./scripts/logs.sh      - ログの確認"
echo ""

echo "🔗 操作系:"
echo "  ./scripts/attach.sh    - コンテナ内部にアタッチ"
echo ""

echo "📋 使用方法:"
echo "  1. 初回起動: ./scripts/start.sh"
echo "  2. 状態確認: ./scripts/status.sh"
echo "  3. ログ確認: ./scripts/logs.sh"
echo "  4. コンテナ操作: ./scripts/attach.sh"
echo "  5. 再起動: ./scripts/restart.sh"
echo "  6. 停止: ./scripts/stop.sh"
echo ""

echo "🌐 アクセス先:"
echo "  - Laravelアプリ: http://localhost:8000"
echo "  - phpMyAdmin: http://localhost:8080"
echo "    (ユーザー: laravel, パスワード: secret)"
echo ""

echo "💡 便利なTips:"
echo "  - 初回起動後は自動でマイグレーションが実行されます"
echo "  - ログは ./scripts/logs.sh で簡単に確認できます"
echo "  - コンテナ内でArtisanコマンドが使えます"
echo "  - 停止時はボリュームの削除を選択できます"
echo ""

echo "📚 詳細情報:"
echo "  - DOCKER_README.md  - Docker環境の詳細説明"
echo "  - README.md         - アプリケーションの説明"
echo "  - ARCHITECTURE.md   - システム設計書" 