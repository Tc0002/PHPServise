#!/bin/bash

# メモリ使用量重視のキューワーカー起動スクリプト（自動停止通知付き）
# 使用方法: ./start-queue-worker.sh

echo "軽量キューワーカーを起動しています..."

# キューワーカーの起動（メモリ最適化設定）
php artisan queue:work \
    --queue=lightweight \
    --timeout=30 \
    --tries=2 \
    --max-jobs=10 \
    --max-time=300 \
    --memory=128 \
    --sleep=3 \
    --verbose

# キューワーカー停止時の通知
EXIT_CODE=$?
echo ""
echo "=========================================="
echo "キューワーカーが停止しました"
echo "終了コード: $EXIT_CODE"
echo "停止時刻: $(date)"
echo "=========================================="

# 停止理由の判定
if [ $EXIT_CODE -eq 0 ]; then
    echo "✅ 正常終了: すべてのジョブが処理されました"
elif [ $EXIT_CODE -eq 1 ]; then
    echo "⚠️  設定による停止: --max-jobs または --max-time に達しました"
else
    echo "❌ エラーによる停止: エラーコード $EXIT_CODE"
fi

echo ""
echo "再起動する場合は以下を実行してください:"
echo "  ./start-queue-worker.sh"
echo "監視コマンド: php artisan queue:monitor"
echo "停止コマンド: php artisan queue:restart" 