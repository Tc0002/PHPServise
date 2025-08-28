<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AWS SDK Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for Amazon Web Services.
    | The configuration file is not stored in the repository since it
    | contains sensitive information that could expose your account.
    |
    */

    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],

    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),

    'version' => 'latest',

    'endpoint' => env('AWS_ENDPOINT'),

    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),

    'bucket' => env('S3_BUCKET', 'laravel-product-system'),

    /*
    |--------------------------------------------------------------------------
    | MinIO Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for MinIO S3-compatible storage
    |
    */

    'minio' => [
        'enabled' => env('MINIO_ENABLED', true),
        'endpoint' => env('AWS_ENDPOINT', 'http://minio:9000'),
        'bucket' => env('S3_BUCKET', 'laravel-product-system'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'use_ssl' => env('MINIO_USE_SSL', false),
        'verify' => env('MINIO_VERIFY_SSL', false),
    ],

];
