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
