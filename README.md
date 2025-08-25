# Laravel Product System Production

[![CI/CD Pipeline](https://github.com/Tc0002/PHPServise/workflows/Local%20CI%2FCD%20Pipeline/badge.svg)](https://github.com/Tc0002/PHPServise/actions)

本格的なLaravel製品管理システムの本番環境用リポジトリです。CI/CDパイプライン、コード品質チェック、Docker化、自動テストが統合されています。

## 🚀 特徴

- **Laravel 12.x** - 最新のLaravelフレームワーク
- **CI/CD統合** - GitHub Actionsによる自動化
- **コード品質** - PHP CodeSniffer、PHPStan統合
- **Docker化** - 本番環境対応のコンテナ化
- **自動テスト** - PHPUnit + Pestによる包括的テスト
- **フロントエンド** - Vite 7.xによる高速ビルド

## 📋 要件

### システム要件
- **PHP**: 8.2以上
- **Node.js**: 20.x以上
- **Composer**: 2.x
- **Docker**: 20.x以上
- **Git**: 2.x以上

### 推奨環境
- **OS**: Ubuntu 22.04 LTS
- **メモリ**: 4GB以上
- **ストレージ**: 20GB以上

## 🏗️ プロジェクト構造

```
laravel-product-system-production/
├── .github/workflows/          # CI/CD設定
│   └── local-ci-cd.yml        # メインワークフロー
├── app/                        # Laravelアプリケーション
│   ├── Http/Controllers/      # コントローラー
│   ├── Models/                # Eloquentモデル
│   ├── Providers/             # サービスプロバイダー
│   └── Dockerfile             # Docker設定
├── docker/                     # Docker設定
│   ├── nginx/conf.d/          # Nginx設定
│   └── supervisor/            # Supervisor設定
├── config/                     # Laravel設定
├── database/                   # データベース関連
├── resources/                  # フロントエンドリソース
├── routes/                     # ルーティング
├── storage/                    # ストレージ
├── tests/                      # テストスイート
├── vendor/                     # Composer依存関係
├── .gitignore                  # Git除外設定
├── artisan                     # Laravelコマンド
├── composer.json               # PHP依存関係
├── package.json                # Node.js依存関係
├── phpcs.xml                   # コード品質チェック
├── phpstan.neon               # 静的解析
├── phpunit.xml                # テスト設定
├── vite.config.js             # フロントエンドビルド
└── PROJECT_STATUS.yaml         # プロジェクト状況記録
```

## 🚀 クイックスタート

### 1. リポジトリのクローン

```bash
git clone https://github.com/Tc0002/PHPServise.git
cd laravel-product-system-production
```

### 2. 依存関係のインストール

```bash
# PHP依存関係
composer install

# Node.js依存関係
npm install
```

### 3. 環境設定

```bash
# 環境変数ファイルの作成
cp .env.example .env

# アプリケーションキーの生成
php artisan key:generate

# データベースの設定
php artisan migrate
```

### 4. 開発サーバーの起動

```bash
# フロントエンドビルド
npm run dev

# Laravel開発サーバー
php artisan serve
```

## 🐳 Docker環境での実行

### 1. Dockerイメージのビルド

```bash
docker build -t laravel-app:latest -f app/Dockerfile .
```

### 2. コンテナの起動

```bash
docker run -d -p 80:80 laravel-app:latest
```

### 3. カスタム設定での起動

```bash
docker run -d \
  -p 80:80 \
  -e APP_ENV=production \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=your-db-host \
  laravel-app:latest
```

## 🧪 テストの実行

### 1. PHPUnitテスト

```bash
# 全テストの実行
php artisan test

# 特定のテストファイル
php artisan test tests/Feature/ExampleTest.php

# カバレッジ付きテスト
./vendor/bin/phpunit --coverage-html coverage
```

### 2. コード品質チェック

```bash
# PSR-12準拠チェック
./vendor/bin/phpcs --standard=PSR12 app/

# 静的解析
./vendor/bin/phpstan analyse --level 0 app/

# 自動修正（可能な場合）
./vendor/bin/phpcbf --standard=PSR12 app/
```

### 3. フロントエンドテスト

```bash
# 開発ビルド
npm run dev

# 本番ビルド
npm run build

# ビルドテスト
npm run build && npm run preview
```

## 🔄 CI/CDパイプライン

### 自動トリガー
- **Push to main/develop**: 自動ビルド・テスト
- **Pull Request**: 品質チェック・テスト実行
- **Manual Dispatch**: 手動実行

### パイプラインの流れ

1. **環境セットアップ**
   - PHP 8.2環境
   - Node.js 20.x環境
   - Composer依存関係

2. **テスト実行**
   - PHPUnitテスト
   - PHP CodeSniffer (PSR-12)
   - PHPStan (Level 0)

3. **ビルド処理**
   - フロントエンドビルド (Vite)
   - Dockerイメージビルド
   - アーティファクト保存

4. **デプロイ準備**
   - イメージのダウンロード
   - ローカル環境での展開手順表示

## 🐧 Ubuntu 22.04での本番環境セットアップ

### 1. システムの更新

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip software-properties-common
```

### 2. PHP 8.2のインストール

```bash
# PHP 8.2リポジトリの追加
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# PHP 8.2と必要な拡張機能のインストール
sudo apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-pgsql \
php8.2-sqlite3 php8.2-bcmath php8.2-mbstring php8.2-xml php8.2-curl \
php8.2-zip php8.2-gd php8.2-intl php8.2-opcache php8.2-redis
```

### 3. Composerのインストール

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### 4. Node.js 20.xのインストール

```bash
# NodeSourceリポジトリの追加
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -

# Node.jsのインストール
sudo apt install -y nodejs

# バージョン確認
node --version
npm --version
```

### 5. Nginxのインストール・設定

```bash
# Nginxのインストール
sudo apt install -y nginx

# Laravel用のNginx設定
sudo tee /etc/nginx/sites-available/laravel << 'EOF'
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/laravel-product-system-production/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# 設定の有効化
sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
```

### 6. PostgreSQLのインストール・設定

```bash
# PostgreSQLのインストール
sudo apt install -y postgresql postgresql-contrib

# PostgreSQLサービスの開始
sudo systemctl start postgresql
sudo systemctl enable postgresql

# データベースとユーザーの作成
sudo -u postgres psql << 'EOF'
CREATE DATABASE laravel_production;
CREATE USER laravel_user WITH ENCRYPTED PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE laravel_production TO laravel_user;
ALTER USER laravel_user CREATEDB;
\q
EOF
```

### 7. アプリケーションのデプロイ

```bash
# アプリケーションディレクトリの作成
sudo mkdir -p /var/www
cd /var/www

# リポジトリのクローン
sudo git clone https://github.com/Tc0002/PHPServise.git laravel-product-system-production
sudo chown -R www-data:www-data laravel-product-system-production
cd laravel-product-system-production

# 依存関係のインストール
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data npm install
sudo -u www-data npm run build

# 環境設定
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate

# データベース設定
sudo -u www-data php artisan migrate --force

# 権限設定
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 8. セキュリティ設定

```bash
# ファイアウォールの設定
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw enable

# SSL証明書の設定（Let's Encrypt）
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

## 🔧 トラブルシューティング

### よくある問題と解決方法

#### 1. 権限エラー
```bash
sudo chown -R www-data:www-data /var/www/laravel-product-system-production
sudo chmod -R 755 /var/www/laravel-product-system-production
sudo chmod -R 775 storage bootstrap/cache
```

#### 2. Composerメモリ不足
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

#### 3. Nginx設定エラー
```bash
sudo nginx -t
sudo systemctl reload nginx
```

#### 4. PHP-FPM接続エラー
```bash
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm
```

## 📚 参考資料

- [Laravel 12.x ドキュメント](https://laravel.com/docs)
- [PHP 8.2 ドキュメント](https://www.php.net/manual/ja/)
- [Node.js 20.x ドキュメント](https://nodejs.org/docs/)
- [Docker ドキュメント](https://docs.docker.com/)
- [Nginx ドキュメント](https://nginx.org/en/docs/)

## 🤝 コントリビューション

1. このリポジトリをフォーク
2. フィーチャーブランチを作成 (`git checkout -b feature/amazing-feature`)
3. 変更をコミット (`git commit -m 'Add amazing feature'`)
4. ブランチにプッシュ (`git push origin feature/amazing-feature`)
5. プルリクエストを作成

## 📄 ライセンス

このプロジェクトはMITライセンスの下で公開されています。詳細は[LICENSE](LICENSE)ファイルを参照してください。

## 📞 サポート

問題や質問がある場合は、以下の方法でお問い合わせください：

- [Issues](https://github.com/Tc0002/PHPServise/issues) - バグ報告・機能要求
- [Discussions](https://github.com/Tc0002/PHPServise/discussions) - 質問・議論

---

**最終更新**: 2025年8月25日  
**バージョン**: 1.0.0  
**メンテナー**: 開発チーム 