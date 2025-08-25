# Laravel Product System Production

## 概要

Laravel 12.25.0を使用した製品管理システムです。CI/CDパイプラインが構築され、Mac環境でのローカル開発が可能です。

## 🚀 特徴

- **Laravel 12.25.0** - 最新のLaravelフレームワーク
- **PHP 8.4.11** - 最新のPHPバージョン
- **CI/CDパイプライン** - GitHub Actionsによる自動化
- **Docker対応** - コンテナ化されたアプリケーション
- **コード品質** - PHP CodeSniffer、PHPStan統合
- **フロントエンド** - Vite + Node.js 20
- **Mac環境対応** - Colimaでの動作確認済み

## 📋 要件

### システム要件
- **PHP**: 8.4.11+
- **Node.js**: 20+
- **Composer**: 2.8+
- **Docker**: Colima対応

### 主要パッケージ
- **Laravel Framework**: ^12.0
- **PHP CodeSniffer**: ^3.8 (PSR-12準拠)
- **PHPStan**: ^1.10 (静的解析)
- **Vite**: ^5.0 (フロントエンドビルド)

## 🏗️ プロジェクト構造

```
laravel-product-system-production/
├── app/                    # アプリケーションロジック
│   ├── Http/Controllers/  # HTTPコントローラー
│   ├── Models/            # Eloquentモデル
│   └── Providers/         # サービスプロバイダー
├── config/                 # 設定ファイル
├── database/               # データベース関連
├── public/                 # 公開ディレクトリ
├── resources/              # リソースファイル
├── routes/                 # ルート定義
├── storage/                # ストレージ
├── tests/                  # テストファイル
├── vendor/                 # Composer依存関係
├── .github/workflows/      # CI/CD設定
├── docker/                 # Docker設定
├── app/Dockerfile          # メインアプリケーション用Dockerfile
└── README.md               # このファイル
```

## 🚀 クイックスタート

### 1. リポジトリのクローン
```bash
git clone https://github.com/Tc0002/PHPServise.git
cd PHPServise/laravel-product-system-production
```

### 2. 依存関係のインストール
```bash
composer install
npm install
```

### 3. 環境設定
```bash
cp .env.example .env
php artisan key:generate
```

### 4. データベース設定
```bash
# SQLiteを使用（開発環境）
echo "DB_CONNECTION=sqlite" >> .env
echo "DB_DATABASE=database/database.sqlite" >> .env
touch database/database.sqlite
```

### 5. データベースマイグレーション
```bash
php artisan migrate
```

### 6. 開発サーバーの起動
```bash
php artisan serve
```

ブラウザで http://127.0.0.1:8000 にアクセスしてください。

## 🐳 Docker環境

### Docker Composeでの起動
```bash
docker-compose up -d
```

### 個別のDockerイメージビルド
```bash
docker build -f app/Dockerfile -t laravel-app .
docker run -d -p 80:80 laravel-app:latest
```

## 🧪 テスト

### PHPUnitテストの実行
```bash
php artisan test
# または
./vendor/bin/phpunit
```

### コード品質チェック
```bash
# PHP CodeSniffer (PSR-12)
./vendor/bin/phpcs

# PHPStan (静的解析)
./vendor/bin/phpstan analyse --level 0 app/
```

## 🔄 CI/CD

### GitHub Actionsワークフロー
- **ファイル**: `.github/workflows/local-ci-cd.yml`
- **トリガー**: プッシュ、プルリクエスト
- **実行環境**: Ubuntu 22.04

### CI/CDパイプラインの流れ
1. **ビルド・テスト**
   - PHP 8.4.11環境セットアップ
   - Composer依存関係インストール
   - PHPUnit/Pestテスト実行
   - コード品質チェック
   - フロントエンドビルド

2. **Dockerビルド**
   - LaravelアプリケーションのDockerイメージ作成
   - イメージの保存・アーティファクト化

3. **ローカルデプロイ**
   - Dockerイメージの読み込み
   - デプロイ手順の表示

## 🖥️ Mac環境での開発

### Colimaの使用
```bash
# Colimaの起動
colima start

# Docker状態確認
docker ps

# Laravelアプリケーションの起動
php artisan serve
```

### 動作確認済み機能
- ✅ Laravel 12.25.0対応
- ✅ PHP 8.4.11環境
- ✅ ブラウザアクセス正常動作
- ✅ 製品管理機能
- ✅ データベース接続

## 📊 現在の状況

### 開発状況
- **開発フェーズ**: 完了
- **ローカル環境**: 動作確認済み
- **CI/CDパイプライン**: 確認待ち
- **デプロイ状況**: 準備完了

### 最近の修正
- **MissingAppKeyException**: 環境変数読み込み問題を解決
- **Laravel 12対応**: 新しい設定方式に対応
- **public/index.php**: 必要なファイルを追加

## 🛠️ トラブルシューティング

### よくある問題

#### 1. APP_KEYエラー
```bash
php artisan key:generate --force
php artisan config:clear
```

#### 2. データベース接続エラー
```bash
# SQLiteファイルの作成
touch database/database.sqlite
php artisan migrate:fresh
```

#### 3. 依存関係の問題
```bash
composer install --no-dev
npm install
```

#### 4. キャッシュの問題
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📚 参考資料

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [PHP 8.4 Documentation](https://www.php.net/manual/en/)
- [Vite Documentation](https://vitejs.dev/)
- [Docker Documentation](https://docs.docker.com/)

## 🤝 貢献

1. このリポジトリをフォーク
2. 機能ブランチを作成 (`git checkout -b feature/AmazingFeature`)
3. 変更をコミット (`git commit -m 'Add some AmazingFeature'`)
4. ブランチにプッシュ (`git push origin feature/AmazingFeature`)
5. プルリクエストを作成

## 📄 ライセンス

このプロジェクトはMITライセンスの下で公開されています。

## 📞 サポート

問題が発生した場合や質問がある場合は、GitHubのIssuesページでお知らせください。

---

**最終更新**: 2025年8月25日  
**バージョン**: 1.0.0  
**ステータス**: 開発完了・CI/CD確認待ち 