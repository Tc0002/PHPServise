#!/bin/bash

# Ubuntu環境でのDocker環境構築状況確認スクリプト

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

echo "🐳 Ubuntu環境でのDocker環境構築状況を確認します..."
echo "=================================================="
echo ""

# システム情報
print_info "システム情報:"
echo "OS: $(lsb_release -d | cut -f2)"
echo "カーネル: $(uname -r)"
echo "アーキテクチャ: $(uname -m)"
echo ""

# Dockerのインストール状況
print_info "Dockerのインストール状況:"
if command -v docker &> /dev/null; then
    print_success "Docker Engine: インストール済み"
    docker --version
else
    print_error "Docker Engine: 未インストール"
fi

if command -v docker-compose &> /dev/null; then
    print_success "Docker Compose: インストール済み"
    docker-compose --version
else
    print_warning "Docker Compose: 未インストール"
fi
echo ""

# Dockerサービスの状態
print_info "Dockerサービスの状態:"
if systemctl is-active --quiet docker; then
    print_success "Dockerサービス: 起動中"
else
    print_error "Dockerサービス: 停止中"
fi

if systemctl is-enabled --quiet docker; then
    print_success "Dockerサービス: 自動起動有効"
else
    print_warning "Dockerサービス: 自動起動無効"
fi
echo ""

# ユーザーグループの確認
print_info "ユーザーグループの確認:"
CURRENT_USER=$(whoami)
if groups $CURRENT_USER | grep -q docker; then
    print_success "ユーザー '$CURRENT_USER' はdockerグループに所属"
else
    print_warning "ユーザー '$CURRENT_USER' はdockerグループに未所属"
fi
echo ""

# ファイアウォール設定
print_info "ファイアウォール設定:"
if command -v ufw &> /dev/null; then
    UFW_STATUS=$(ufw status | head -1)
    echo "UFW状態: $UFW_STATUS"
    
    # 必要なポートの確認
    echo "開放ポート:"
ufw status numbered | grep -E "(22|80|443|8000|8080|5432)" || echo "  必要なポートが開放されていません"
else
    print_warning "UFWがインストールされていません"
fi
echo ""

# システムリソース
print_info "システムリソース:"
echo "メモリ: $(free -h | grep Mem | awk '{print $2}')"
echo "ディスク容量: $(df -h / | tail -1 | awk '{print $4}') 利用可能"
echo "CPU: $(nproc) コア"
echo ""

# ネットワーク設定
print_info "ネットワーク設定:"
echo "IPアドレス: $(hostname -I | awk '{print $1}')"
echo "ホスト名: $(hostname)"
echo ""

# Dockerイメージとコンテナ
print_info "Dockerイメージとコンテナ:"
if command -v docker &> /dev/null; then
    echo "Dockerイメージ数: $(docker images -q | wc -l)"
    echo "実行中コンテナ数: $(docker ps -q | wc -l)"
    echo "全コンテナ数: $(docker ps -aq | wc -l)"
else
    echo "Dockerがインストールされていないため確認できません"
fi
echo ""

# 推奨設定の確認
print_info "推奨設定の確認:"
echo "スワップ設定:"
if grep -q "vm.swappiness" /etc/sysctl.conf; then
    print_success "スワップ設定: 最適化済み"
else
    print_warning "スワップ設定: 未設定"
fi

echo "ファイルディスクリプタ制限:"
if grep -q "fs.file-max" /etc/sysctl.conf; then
    print_success "ファイルディスクリプタ制限: 最適化済み"
else
    print_warning "ファイルディスクリプタ制限: 未設定"
fi

echo "ユーザー制限:"
if grep -q "docker" /etc/security/limits.conf; then
    print_success "ユーザー制限: 最適化済み"
else
    print_warning "ユーザー制限: 未設定"
fi
echo ""

# 総合評価
echo "=================================================="
print_info "総合評価:"

DOCKER_INSTALLED=false
DOCKER_RUNNING=false
USER_IN_GROUP=false
PORTS_OPEN=false

if command -v docker &> /dev/null; then
    DOCKER_INSTALLED=true
fi

if systemctl is-active --quiet docker; then
    DOCKER_RUNNING=true
fi

if groups $CURRENT_USER | grep -q docker; then
    USER_IN_GROUP=true
fi

if command -v ufw &> /dev/null && ufw status | grep -q "8000" && ufw status | grep -q "5432"; then
    PORTS_OPEN=true
fi

if $DOCKER_INSTALLED && $DOCKER_RUNNING && $USER_IN_GROUP; then
    print_success "✅ Docker環境は正常に動作しています！"
    echo ""
    print_info "次のステップ:"
    echo "1. Laravelプロジェクトディレクトリに移動"
    echo "2. ./scripts/start.sh を実行してLaravelを起動"
elif $DOCKER_INSTALLED && !$DOCKER_RUNNING; then
    print_warning "⚠️ Dockerはインストールされていますが、サービスが停止しています"
    echo "解決方法: sudo systemctl start docker"
elif !$DOCKER_INSTALLED; then
    print_error "❌ Dockerがインストールされていません"
    echo "解決方法: sudo ./install-docker.sh"
else
    print_warning "⚠️ Docker環境に問題があります"
    echo "詳細は上記の確認結果を参照してください"
fi

echo ""
print_info "詳細なログ:"
echo "- Dockerサービス: sudo systemctl status docker"
echo "- UFW状態: sudo ufw status"
echo "- システムログ: sudo journalctl -u docker" 