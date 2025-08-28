#!/bin/bash

# Laravelキューワーカー再起動スクリプト
# 使用方法: ./restart-queue-worker.sh

echo "🔄 Laravelキューワーカーを再起動しています..."
echo "=========================================="

# 現在のディレクトリを確認
if [ ! -f "artisan" ]; then
    echo "❌ エラー: artisanファイルが見つかりません。Laravelプロジェクトのルートディレクトリで実行してください。"
    exit 1
fi

# 既存のキューワーカープロセスの確認
echo "🔍 既存のキューワーカープロセスを確認中..."
WORKER_PIDS=$(ps aux | grep "queue:work.*lightweight" | grep -v grep | awk '{print $2}')

if [ -n "$WORKER_PIDS" ]; then
    echo "📋 実行中のキューワーカープロセス:"
    ps aux | grep "queue:work.*lightweight" | grep -v grep
    
    echo ""
    echo "🛑 キューワーカーを停止中..."
    
    # 各プロセスを安全に停止
    for PID in $WORKER_PIDS; do
        echo "プロセス $PID を停止中..."
        kill -TERM $PID 2>/dev/null || true
        
        # プロセスが停止するまで待機
        for i in {1..10}; do
            if ! kill -0 $PID 2>/dev/null; then
                echo "✅ プロセス $PID が停止しました"
                break
            fi
            sleep 1
        done
        
        # 強制終了が必要な場合
        if kill -0 $PID 2>/dev/null; then
            echo "⚠️  プロセス $PID を強制終了します"
            kill -KILL $PID 2>/dev/null || true
        fi
    done
    
    # プロセスが完全に停止するまで待機
    sleep 3
    
    # 残存プロセスの確認
    REMAINING_PIDS=$(ps aux | grep "queue:work.*lightweight" | grep -v grep | awk '{print $2}')
    if [ -n "$REMAINING_PIDS" ]; then
        echo "⚠️  残存プロセスがあります: $REMAINING_PIDS"
        echo "強制終了します..."
        kill -KILL $REMAINING_PIDS 2>/dev/null || true
    fi
else
    echo "✅ 実行中のキューワーカープロセスはありません"
fi

# キューの状況確認
echo ""
echo "📊 キューの状況を確認中..."
php artisan queue:monitor 2>/dev/null || echo "キューモニターが利用できません"

# キューの再起動
echo ""
echo "🔄 キューの再起動を実行中..."
php artisan queue:restart

# キューワーカーの再起動
echo ""
echo "🚀 キューワーカーを再起動中..."
nohup php artisan queue:work \
    --queue=lightweight \
    --timeout=30 \
    --tries=2 \
    --max-jobs=10 \
    --max-time=300 \
    --memory=128 \
    --sleep=3 \
    --verbose > storage/logs/queue-worker.log 2>&1 &

# プロセスIDの保存
WORKER_PID=$!
echo $WORKER_PID > storage/framework/queue-worker.pid
echo "✅ キューワーカーが再起動されました (PID: $WORKER_PID)"

# 起動確認
echo ""
echo "🔍 キューワーカーの起動を確認中..."
sleep 3

if kill -0 $WORKER_PID 2>/dev/null; then
    echo "✅ キューワーカーが正常に起動しています"
    
    # プロセス情報の表示
    echo ""
    echo "📋 キューワーカーの情報:"
    ps -p $WORKER_PID -o pid,ppid,etime,pcpu,pmem,command --no-headers
    
    # キューの状況確認
    echo ""
    echo "📊 キューの状況:"
    php artisan queue:monitor 2>/dev/null || echo "キューモニターが利用できません"
    
    echo ""
    echo "🎉 キューワーカーの再起動が完了しました！"
    echo ""
    echo "📝 ログの確認:"
    echo "   tail -f storage/logs/queue-worker.log"
    echo ""
    echo "👀 監視:"
    echo "   ./monitor-queue-worker.sh"
    echo ""
    echo "🔄 再起動:"
    echo "   ./restart-queue-worker.sh"
else
    echo "❌ キューワーカーの起動に失敗しました"
    echo "ログを確認してください: storage/logs/queue-worker.log"
    exit 1
fi

echo ""
echo "==========================================" 