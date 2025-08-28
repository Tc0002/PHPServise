#!/bin/bash

# キューワーカー監視スクリプト
# 使用方法: ./monitor-queue-worker.sh

echo "キューワーカーの監視を開始しています..."
echo "監視間隔: 5秒"
echo "停止を検出したら通知します"
echo ""

# 監視ループ
while true; do
    # キューワーカーのプロセス確認
    WORKER_PID=$(ps aux | grep "queue:work.*lightweight" | grep -v grep | awk '{print $2}')
    
    if [ -z "$WORKER_PID" ]; then
        echo ""
        echo "🚨 キューワーカーが停止しました！"
        echo "停止時刻: $(date)"
        echo "=========================================="
        
        # キューの状況確認
        echo "キューの状況:"
        php artisan queue:monitor lightweight 2>/dev/null || echo "キューモニターが利用できません"
        
        # ジョブの状況確認
        echo ""
        echo "ジョブの状況:"
        php artisan tinker --execute="echo '待機中ジョブ: ' . \DB::table('jobs')->count() . '\n';" 2>/dev/null || echo "ジョブ状況が確認できません"
        
        echo ""
        echo "再起動オプション:"
        echo "1. 自動再起動: ./start-queue-worker.sh"
        echo "2. 手動起動: php artisan queue:work --queue=lightweight --timeout=30 --tries=2 --max-jobs=10 --max-time=300 --memory=128 --sleep=3"
        echo "3. 監視継続: このスクリプトを継続実行"
        echo ""
        
        # 自動再起動の確認
        read -p "自動再起動しますか？ (y/n): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            echo "キューワーカーを再起動しています..."
            ./start-queue-worker.sh &
            echo "再起動完了。監視を継続します。"
        fi
        
        echo "監視を継続中..."
    else
        # プロセス情報の表示
        WORKER_INFO=$(ps -p $WORKER_PID -o pid,ppid,etime,pcpu,pmem,command --no-headers)
        echo -ne "\r監視中... PID: $WORKER_PID | 稼働時間: $(echo $WORKER_INFO | awk '{print $3}') | CPU: $(echo $WORKER_INFO | awk '{print $4}')% | メモリ: $(echo $WORKER_INFO | awk '{print $5}')%"
    fi
    
    sleep 5
done 