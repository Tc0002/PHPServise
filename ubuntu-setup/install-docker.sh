#!/bin/bash

# Ubuntu 22.04LTS 用 Docker環境構築スクリプト
# 使用方法: sudo ./install-docker.sh

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

# 管理者権限チェック
if [[ $EUID -ne 0 ]]; then
    print_error "このスクリプトは管理者権限で実行する必要があります"
    print_info "使用方法: sudo ./install-docker.sh"
    exit 1
fi

print_info "Ubuntu 22.04LTS 用 Docker環境構築を開始します..."
echo ""

# システムアップデート
print_info "システムパッケージを更新中..."
apt update && apt upgrade -y
print_success "システムパッケージの更新完了"

# 必要なパッケージのインストール
print_info "必要なパッケージをインストール中..."
apt install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release \
    software-properties-common \
    git \
    unzip \
    wget
print_success "必要なパッケージのインストール完了"

# Docker GPGキーの追加
print_info "Docker GPGキーを追加中..."
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
print_success "Docker GPGキーの追加完了"

# Dockerリポジトリの追加
print_info "Dockerリポジトリを追加中..."
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null
print_success "Dockerリポジトリの追加完了"

# パッケージリストの更新
print_info "パッケージリストを更新中..."
apt update
print_success "パッケージリストの更新完了"

# Docker Engineのインストール
print_info "Docker Engineをインストール中..."
apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
print_success "Docker Engineのインストール完了"

# Docker Composeのインストール
print_info "Docker Composeをインストール中..."
curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
print_success "Docker Composeのインストール完了"

# Dockerサービスの起動と有効化
print_info "Dockerサービスを起動・有効化中..."
systemctl start docker
systemctl enable docker
print_success "Dockerサービスの起動・有効化完了"

# 現在のユーザーをdockerグループに追加
print_info "現在のユーザーをdockerグループに追加中..."
usermod -aG docker $SUDO_USER
print_success "ユーザーをdockerグループに追加完了"

# Dockerの動作確認
print_info "Dockerの動作確認中..."
docker --version
docker-compose --version
print_success "Dockerの動作確認完了"

# ファイアウォール設定
print_info "ファイアウォールを設定中..."
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw allow 8000/tcp  # Laravel
ufw allow 8080/tcp  # pgAdmin
ufw allow 5432/tcp  # PostgreSQL
ufw --force enable
print_success "ファイアウォール設定完了"

# システム最適化
print_info "システム最適化を実行中..."
# スワップ設定
if ! grep -q "vm.swappiness" /etc/sysctl.conf; then
    echo "vm.swappiness=10" >> /etc/sysctl.conf
fi

# ファイルディスクリプタ制限
if ! grep -q "fs.file-max" /etc/sysctl.conf; then
    echo "fs.file-max=1000000" >> /etc/sysctl.conf
fi

# ユーザー制限
if ! grep -q "docker" /etc/security/limits.conf; then
    echo "$SUDO_USER soft nofile 65536" >> /etc/security/limits.conf
    echo "$SUDO_USER hard nofile 65536" >> /etc/security/limits.conf
fi

sysctl -p
print_success "システム最適化完了"

echo ""
print_success "Docker環境構築が完了しました！"
echo ""
print_info "次の手順:"
echo "1. システムを再起動してください: sudo reboot"
echo "2. 再起動後、以下のコマンドでDockerが動作することを確認:"
echo "   docker --version"
echo "   docker-compose --version"
echo ""
print_info "Laravelプロジェクトの起動:"
echo "1. プロジェクトディレクトリに移動"
echo "2. ./scripts/start.sh を実行"
echo ""
print_warning "注意: 再起動後にdockerグループの権限が有効になります" 