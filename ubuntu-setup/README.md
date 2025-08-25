# Ubuntu 22.04LTS での Docker環境構築とLaravelプロジェクトデプロイ

## 概要
このディレクトリには、Ubuntu 22.04LTSでDocker環境を構築し、Laravel商品管理システムをデプロイするためのスクリプトとドキュメントが含まれています。

## 前提条件
- Ubuntu 22.04LTS がインストールされたサーバー
- 管理者権限（sudo）を持つユーザー
- インターネット接続
- 最小システム要件：
  - メモリ: 2GB以上（推奨4GB）
  - ディスク容量: 20GB以上
  - CPU: 2コア以上

## ファイル構成
```
ubuntu-setup/
├── README.md              # このファイル
├── install-docker.sh      # Docker環境構築スクリプト
├── check-environment.sh   # 環境確認スクリプト
└── deploy-laravel.sh      # Laravelプロジェクトデプロイスクリプト
```

## セットアップ手順

### **ステップ1: Docker環境の構築**

#### **1.1 スクリプトの実行権限を付与**
```bash
chmod +x ubuntu-setup/*.sh
```

#### **1.2 Docker環境構築スクリプトを実行**
```bash
sudo ./ubuntu-setup/install-docker.sh
```

**このスクリプトが実行すること:**
- システムパッケージの更新
- 必要なパッケージのインストール
- Docker Engineのインストール
- Docker Composeのインストール
- Dockerサービスの起動・有効化
- ユーザーをdockerグループに追加
- ファイアウォール設定
- システム最適化

#### **1.3 システムの再起動**
```bash
sudo reboot
```

**重要:** 再起動後にdockerグループの権限が有効になります。

#### **1.4 環境構築の確認**
```bash
./ubuntu-setup/check-environment.sh
```

### **ステップ2: Laravelプロジェクトのデプロイ**

#### **2.1 プロジェクトディレクトリに移動**
```bash
cd /path/to/your/laravel-project
```

#### **2.2 デプロイスクリプトを実行**
```bash
./ubuntu-setup/deploy-laravel.sh
```

**このスクリプトが実行すること:**
- Docker環境の確認
- システムリソースの確認
- 必要なディレクトリの作成
- 権限の設定
- 環境変数ファイルの更新
- Dockerイメージのビルド
- コンテナの起動
- アプリケーションキーの生成
- データベースのマイグレーション
- 権限の設定
- 動作確認

## アクセス方法

### **Webアプリケーション**
- **Laravel商品管理システム**: http://localhost:8000
- **pgAdmin 4**: http://localhost:8080
  - メールアドレス: `admin@laravel.local`
  - パスワード: `admin`

### **SSH接続**
```bash
ssh username@your-server-ip
```

## 管理コマンド

### **Docker環境の管理**
```bash
# コンテナの状態確認
docker-compose ps

# ログの確認
docker-compose logs -f

# コンテナの再起動
docker-compose restart

# コンテナの停止
docker-compose down
```

### **Laravelアプリケーションの管理**
```bash
# コンテナ内でArtisanコマンドを実行
docker-compose exec app php artisan migrate
docker-compose exec app php artisan make:controller ProductController

# コンテナ内にアクセス
docker-compose exec app bash
```

### **ショートカットスクリプトの使用**
```bash
# 状態確認
./scripts/status.sh

# ログ確認
./scripts/logs.sh

# 再起動
./scripts/restart.sh

# 停止
./scripts/stop.sh

# ヘルプ表示
./scripts/help.sh
```

## トラブルシューティング

### **よくある問題と解決方法**

#### **1. Dockerサービスが起動しない**
```bash
# サービス状態の確認
sudo systemctl status docker

# サービスの再起動
sudo systemctl restart docker

# ログの確認
sudo journalctl -u docker
```

#### **2. 権限エラーが発生する**
```bash
# ユーザーがdockerグループに所属しているか確認
groups $USER

# dockerグループに追加（必要に応じて）
sudo usermod -aG docker $USER

# 新しいセッションでログインし直す
```

#### **3. ポートが使用中**
```bash
# 使用中のポートを確認
sudo lsof -i :8000
sudo lsof -i :8080
sudo lsof -i :5432

# 必要に応じてプロセスを停止
sudo pkill -f process_name
```

#### **4. ファイアウォールの問題**
```bash
# UFW状態の確認
sudo ufw status

# 必要なポートを開放
sudo ufw allow 8000/tcp
sudo ufw allow 8080/tcp
sudo ufw allow 5432/tcp
```

#### **5. ディスク容量不足**
```bash
# ディスク使用量の確認
df -h

# Docker関連ファイルのクリーンアップ
docker system prune -a
docker volume prune
```

## パフォーマンス最適化

### **システム設定の最適化**
```bash
# スワップ設定の確認
cat /proc/sys/vm/swappiness

# ファイルディスクリプタ制限の確認
ulimit -n

# カーネルパラメータの確認
sysctl -a | grep -E "(vm.swappiness|fs.file-max)"
```

### **Docker設定の最適化**
```bash
# Dockerデーモンの設定確認
sudo cat /etc/docker/daemon.json

# リソース制限の設定
docker run --memory=512m --cpus=1 container_name
```

## セキュリティ考慮事項

### **ファイアウォール設定**
- 必要最小限のポートのみ開放
- SSH接続の制限
- 不正アクセスの監視

### **Dockerセキュリティ**
- 非特権ユーザーの使用
- イメージのスキャン
- コンテナの隔離

### **アプリケーションセキュリティ**
- Laravel標準セキュリティ機能の活用
- 定期的なセキュリティアップデート
- ログの監視

## バックアップ・復旧

### **データベースのバックアップ**
```bash
# バックアップの作成
docker-compose exec db pg_dump -U laravel -d laravel > backup.sql

# バックアップの復元
docker-compose exec -T db psql -U laravel -d laravel < backup.sql
```

### **アプリケーションファイルのバックアップ**
```bash
# プロジェクト全体のバックアップ
tar -czf laravel-backup-$(date +%Y%m%d).tar.gz . --exclude=vendor --exclude=node_modules
```

## 監視・ログ

### **システム監視**
```bash
# システムリソースの監視
htop
iotop
nethogs

# Dockerリソースの監視
docker stats
```

### **ログ監視**
```bash
# システムログ
sudo journalctl -f

# Dockerログ
docker-compose logs -f

# Laravelログ
docker-compose exec app tail -f storage/logs/laravel.log
```

## 今後の拡張

### **短期計画**
- Redisキャッシュの追加
- ロードバランサーの導入
- 監視ツールの統合

### **中期計画**
- CI/CDパイプラインの構築
- 自動バックアップの実装
- スケーリング機能の追加

### **長期計画**
- Kubernetesへの移行
- マルチリージョンデプロイ
- 災害復旧システムの構築

## サポート・問い合わせ

問題が発生した場合は、以下の順序で確認してください：

1. スクリプトの実行ログ
2. Dockerサービスの状態
3. システムリソースの使用状況
4. ファイアウォール設定
5. ネットワーク接続

詳細なログが必要な場合は、各スクリプトの`--verbose`オプションを使用してください。 