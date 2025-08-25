<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\S3\S3Client;
use App\Services\S3FileService;
use App\Services\SearchService;

class S3ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // AWS S3クライアントの登録（MinIO対応）
        $this->app->singleton(S3Client::class, function ($app) {
            $config = [
                'version' => 'latest',
                'region'  => config('aws.region'),
                'credentials' => [
                    'key'    => config('aws.credentials.key'),
                    'secret' => config('aws.credentials.secret'),
                ],
            ];

            // MinIOエンドポイントが設定されている場合
            if (config('aws.endpoint')) {
                $config['endpoint'] = config('aws.endpoint');
                $config['use_path_style_endpoint'] = config('aws.use_path_style_endpoint', true);
                
                // ローカル環境ではSSL無効
                if (config('app.env') === 'local' || config('app.env') === 'development') {
                    $config['use_ssl'] = false;
                    $config['verify'] = false;
                }
            }

            return new S3Client($config);
        });

        // S3ファイルサービスの登録
        $this->app->singleton(S3FileService::class, function ($app) {
            return new S3FileService(
                $app->make(S3Client::class),
                config('aws.bucket'),
                config('filesystems.disks.s3_uploads.root', 'uploads')
            );
        });

        // 検索サービスの登録
        $this->app->singleton(SearchService::class, function ($app) {
            return new SearchService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 