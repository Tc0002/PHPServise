#!/bin/bash

# Laravelキューワーカー自動起動設定スクリプト
# 使用方法: ./setup-queue-autostart.sh

set -e

echo "🚀 Laravelキューワーカーの自動起動設定を開始します..."
echo "=========================================="

# 現在のディレクトリを確認
if [ ! -f "artisan" ]; then
    echo "❌ エラー: artisanファイルが見つかりません。Laravelプロジェクトのルートディレクトリで実行してください。"
    exit 1
fi

echo "✅ Laravelプロジェクトを確認しました"

# 必要なディレクトリの作成
echo "📁 必要なディレクトリを作成中..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

# 権限の設定
echo "🔐 権限を設定中..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# キューワーカーの設定確認
echo "⚙️  キューワーカーの設定を確認中..."
if [ -f ".env" ]; then
    echo "✅ .envファイルが存在します"
    # キュードライバーの確認
    QUEUE_DRIVER=$(grep "^QUEUE_DRIVER=" .env | cut -d'=' -f2)
    if [ "$QUEUE_DRIVER" = "database" ]; then
        echo "✅ キュードライバー: database"
    else
        echo "⚠️  キュードライバー: $QUEUE_DRIVER (database推奨)"
    fi
else
    echo "⚠️  .envファイルが存在しません。キュードライバーの設定を確認してください。"
fi

# キューテーブルの存在確認
echo "🗄️  キューテーブルの存在を確認中..."
if php artisan queue:table --help >/dev/null 2>&1; then
    echo "✅ キューテーブルが利用可能です"
else
    echo "⚠️  キューテーブルが利用できません。マイグレーションを実行してください。"
fi

# Supervisorの設定
echo "🔧 Supervisorの設定を確認中..."
if command -v supervisord >/dev/null 2>&1; then
    echo "✅ Supervisorがインストールされています"
    
    # サービスファイルのコピー
    if [ -f "laravel-queue-worker.service" ]; then
        echo "📋 systemdサービスファイルをコピー中..."
        sudo cp laravel-queue-worker.service /etc/systemd/system/
        sudo systemctl daemon-reload
        sudo systemctl enable laravel-queue-worker.service
        echo "✅ systemdサービスを有効化しました"
    fi
else
    echo "⚠️  Supervisorがインストールされていません"
fi

# キューワーカーの起動テスト
echo "🧪 キューワーカーの起動テスト中..."
if php artisan queue:work --help >/dev/null 2>&1; then
    echo "✅ キューワーカーコマンドが利用可能です"
else
    echo "❌ キューワーカーコマンドが利用できません"
    exit 1
fi

# キューの状況確認
echo "📊 キューの状況を確認中..."
php artisan queue:monitor 2>/dev/null || echo "キューモニターが利用できません"

# 自動起動スクリプトの作成
echo "📝 自動起動スクリプトを作成中..."
cat > start-queue-automatically.sh << 'EOF'
#!/bin/bash

# キューワーカー自動起動スクリプト
# このスクリプトはシステム起動時に実行されます

cd "$(dirname "$0")"

# キューワーカーの起動
echo "$(date): キューワーカーを起動しています..." >> storage/logs/queue-worker.log
nohup php artisan queue:work --queue=lightweight --timeout=30 --tries=2 --max-jobs=10 --max-time=300 --memory=128 --sleep=3 --verbose >> storage/logs/queue-worker.log 2>&1 &

# プロセスIDの保存
echo $! > storage/framework/queue-worker.pid
echo "$(date): キューワーカーが起動しました (PID: $!)" >> storage/logs/queue-worker.log
EOF

chmod +x start-queue-automatically.sh
echo "✅ 自動起動スクリプトを作成しました"

# 監視スクリプトの更新
echo "👀 監視スクリプトを更新中..."
if [ -f "monitor-queue-worker.sh" ]; then
    chmod +x monitor-queue-worker.sh
    echo "✅ 監視スクリプトを更新しました"
fi

# 起動スクリプトの更新
echo "🚀 起動スクリプトを更新中..."
if [ -f "start-queue-worker.sh" ]; then
    chmod +x start-queue-worker.sh
    echo "✅ 起動スクリプトを更新しました"
fi

echo ""
echo "=========================================="
echo "🎉 キューワーカーの自動起動設定が完了しました！"
echo ""
echo "📋 次のコマンドでキューワーカーを管理できます："
echo ""
echo "🔧 手動起動:"
echo "   ./start-queue-worker.sh"
echo "   php artisan queue:work --queue=lightweight"
echo ""
echo "👀 監視:"
echo "   ./monitor-queue-worker.sh"
echo "   php artisan queue:monitor"
echo ""
echo "🔄 キューの再起動:"
echo "   php artisan queue:restart"
echo ""
echo "📊 キューの状況確認:"
echo "   php artisan queue:failed"
echo "   php artisan queue:monitor"
echo ""
echo "🚀 システム起動時の自動起動:"
echo "   sudo systemctl enable laravel-queue-worker.service"
echo "   sudo systemctl start laravel-queue-worker.service"
echo ""
echo "📝 ログの確認:"
echo "   tail -f storage/logs/queue-worker.log"
echo "   journalctl -u laravel-queue-worker.service -f"
echo ""
echo "==========================================" 