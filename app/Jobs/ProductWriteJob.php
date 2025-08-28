<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ProductWriteJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    // メモリ使用量最適化設定
    public $timeout = 30;        // 30秒でタイムアウト
    public $tries = 2;           // 再試行回数2回
    public $maxExceptions = 1;   // 例外時の再試行制限
    public $deleteWhenMissingModels = true; // モデル削除時の自動クリーンアップ

    protected $operation;
    protected $data;
    protected $productId;

    public function __construct($operation, $data, $productId = null)
    {
        $this->operation = $operation;
        $this->data = $data;
        $this->productId = $productId;

        // 軽量キュー設定
        $this->onQueue('lightweight');
        $this->delay(now()->addSeconds(1)); // 1秒遅延
    }

    public function handle()
    {
        // 最小限の処理でメモリ使用量を抑制
        try {
            switch ($this->operation) {
                case 'create':
                    Product::create($this->data);
                    break;
                case 'update':
                    Product::find($this->productId)->update($this->data);
                    break;
                case 'delete':
                    Product::find($this->productId)->delete();
                    break;
            }
        } catch (\Exception $e) {
            \Log::error('Product write job failed: ' . $e->getMessage());
            return false;
        }
    }

    public function failed(\Throwable $exception)
    {
        // 失敗時の軽量処理
        \Log::error('Product write job permanently failed: ' . $exception->getMessage());
    }
}
