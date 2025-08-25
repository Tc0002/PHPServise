#!/bin/bash

# Ubuntu環境でのLaravelプロジェクトデプロイスクリプト

set -e

# 色付き出力用の関数
print_info() {
    echo -e "\033[1;34m[INFO]\033[0m $1"
}

print_success() {
    echo -e "\033[1;32m[SUCCESS]\033[0m $1"
}

print_error() {
    echo -e "\033[1;31m[ERROR]\033[0m $1"
}

print_warning() {
    echo -e "\033[1;33m[WARNING]\033[0m $1"
}

echo "🚀 Ubuntu環境でのLaravelプロジェクトデプロイを開始します..."
echo "=================================================="
echo ""

# 現在のディレクトリ確認
if [[ ! -f "docker-compose.yml" ]]; then
    print_error "このスクリプトはLaravelプロジェクトのルートディレクトリで実行してください"
    exit 1
fi

# Docker環境の確認
print_info "Docker環境の確認中..."
if ! command -v docker &> /dev/null; then
    print_error "Dockerがインストールされていません"
    print_info "先に sudo ./ubuntu-setup/install-docker.sh を実行してください"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Composeがインストールされていません"
    print_info "先に sudo ./ubuntu-setup/install-docker.sh を実行してください"
    exit 1
fi

print_success "Docker環境: OK"
echo ""

# システムリソースの確認
print_info "システムリソースの確認中..."
MEMORY_GB=$(free -g | grep Mem | awk '{print $2}')
DISK_GB=$(df -BG / | tail -1 | awk '{print $4}' | sed 's/G//')
CPU_CORES=$(nproc)

echo "メモリ: ${MEMORY_GB}GB"
echo "ディスク容量: ${DISK_GB}GB 利用可能"
echo "CPU: ${CPU_CORES} コア"

if [[ $MEMORY_GB -lt 2 ]]; then
    print_warning "メモリが2GB未満です。パフォーマンスに影響する可能性があります"
fi

if [[ $DISK_GB -lt 10 ]]; then
    print_warning "ディスク容量が10GB未満です。十分な容量を確保してください"
fi

if [[ $CPU_CORES -lt 2 ]]; then
    print_warning "CPUコア数が2未満です。ビルドに時間がかかる可能性があります"
fi
echo ""

# 必要なディレクトリの作成
print_info "必要なディレクトリを作成中..."
mkdir -p docker/mysql/data
mkdir -p docker/nginx/logs
mkdir -p docker/php/logs
print_success "ディレクトリ作成完了"
echo ""

# 権限の設定
print_info "権限を設定中..."
sudo chown -R $USER:$USER docker/
chmod -R 755 docker/
print_success "権限設定完了"
echo ""

# 環境変数ファイルの確認
print_info "環境変数ファイルの確認中..."
if [[ ! -f ".env" ]]; then
    print_error ".envファイルが見つかりません"
    print_info "cp .env.example .env を実行してから再度実行してください"
    exit 1
fi

# データベース設定の確認
if grep -q "DB_HOST=127.0.0.1" .env; then
    print_info "データベース設定をDocker用に更新中..."
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=laravel/' .env
    sed -i 's/DB_USERNAME=.*/DB_USERNAME=laravel/' .env
    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=secret/' .env
    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
    sed -i 's/DB_PORT=.*/DB_PORT=5432/' .env
    print_success "データベース設定更新完了"
fi
echo ""

# Dockerイメージのビルド
print_info "Dockerイメージをビルド中..."
print_warning "初回ビルドには時間がかかります（10-30分程度）"
docker-compose build --no-cache
print_success "Dockerイメージビルド完了"
echo ""

# コンテナの起動
print_info "コンテナを起動中..."
docker-compose up -d
print_success "コンテナ起動完了"
echo ""

# 起動完了まで待機
print_info "コンテナの起動完了を待機中..."
sleep 15

# コンテナの状態確認
print_info "コンテナの状態確認中..."
docker-compose ps
echo ""

# アプリケーションキーの生成
print_info "アプリケーションキーを生成中..."
docker-compose exec app php artisan key:generate
print_success "アプリケーションキー生成完了"
echo ""

# データベースのマイグレーション
print_info "データベースのマイグレーションを実行中..."
docker-compose exec app php artisan migrate
print_success "マイグレーション完了"
echo ""

# ストレージディレクトリの権限設定
print_info "ストレージディレクトリの権限を設定中..."
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
print_success "権限設定完了"
echo ""

# ファイアウォール設定の確認
print_info "ファイアウォール設定を確認中..."
if command -v ufw &> /dev/null; then
    UFW_STATUS=$(ufw status | head -1)
    if [[ $UFW_STATUS == *"active"* ]]; then
        print_success "UFW: 有効"
        echo "開放ポート:"
        ufw status numbered | grep -E "(8000|8080|3306)" || echo "  必要なポートが開放されていません"
    else
        print_warning "UFW: 無効"
    fi
else
    print_warning "UFWがインストールされていません"
fi
echo ""

# 動作確認
print_info "動作確認中..."
echo "Laravelアプリケーション: http://localhost:8000"
echo "pgAdmin 4: http://localhost:8080"
echo "  - メールアドレス: admin@laravel.local"
echo "  - パスワード: admin"
echo ""

# ヘルスチェック
print_info "ヘルスチェックを実行中..."
if curl -s http://localhost:8000 > /dev/null; then
    print_success "Laravelアプリケーション: 正常動作"
else
    print_warning "Laravelアプリケーション: 応答なし（起動中かもしれません）"
fi

if curl -s http://localhost:8080 > /dev/null; then
    print_success "phpMyAdmin: 正常動作"
else
    print_warning "phpMyAdmin: 応答なし（起動中かもしれません）"
fi
echo ""

print_success "🎉 Laravelプロジェクトのデプロイが完了しました！"
echo ""
print_info "次のステップ:"
echo "1. ブラウザで http://localhost:8000 にアクセス"
echo "2. 商品管理システムの動作確認"
echo "3. 必要に応じてデータの投入"
echo ""
print_info "管理コマンド:"
echo "- 状態確認: ./scripts/status.sh"
echo "- ログ確認: ./scripts/logs.sh"
echo "- 再起動: ./scripts/restart.sh"
echo "- 停止: ./scripts/stop.sh"
echo ""
print_info "トラブルシューティング:"
echo "- ログ確認: docker-compose logs -f"
echo "- コンテナ再起動: docker-compose restart"
echo "- 完全リセット: ./scripts/start.sh" 