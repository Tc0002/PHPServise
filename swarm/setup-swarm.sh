#!/bin/bash

# Docker Swarmクラスタセットアップスクリプト
# 使用方法: ./setup-swarm.sh [manager|worker] [NODE_IP]

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

# 引数の確認
if [[ $# -lt 1 ]]; then
    print_error "使用方法: $0 [manager|worker] [NODE_IP]"
    print_info "例: $0 manager 192.168.1.100"
    print_info "例: $0 worker 192.168.1.101"
    exit 1
fi

NODE_TYPE=$1
NODE_IP=${2:-$(hostname -I | awk '{print $1}')}

echo "🐳 Docker Swarmクラスタセットアップを開始します..."
echo "ノードタイプ: $NODE_TYPE"
echo "ノードIP: $NODE_IP"
echo ""

# Docker Swarmの状態確認
if docker info | grep -q "Swarm: active"; then
    print_warning "Docker Swarmは既にアクティブです"
    print_info "現在の状態:"
    docker node ls
    echo ""
    
    if [[ $NODE_TYPE == "manager" ]]; then
        print_info "Managerノードとして設定済みです"
        exit 0
    else
        print_info "Workerノードとして設定済みです"
        exit 0
    fi
fi

# 管理者権限チェック
if [[ $EUID -ne 0 ]]; then
    print_warning "管理者権限がありません。sudoを使用します"
    SUDO_CMD="sudo"
else
    SUDO_CMD=""
fi

# システム要件の確認
print_info "システム要件を確認中..."
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

# ファイアウォール設定
print_info "ファイアウォールを設定中..."
if command -v ufw &> /dev/null; then
    # Swarm用ポートの開放
    $SUDO_CMD ufw allow 2377/tcp  # Swarm管理ポート
    $SUDO_CMD ufw allow 7946/tcp  # ノード間通信
    $SUDO_CMD ufw allow 7946/udp  # ノード間通信
    $SUDO_CMD ufw allow 4789/udp  # Overlayネットワーク
    $SUDO_CMD ufw allow 80/tcp    # HTTP
    $SUDO_CMD ufw allow 443/tcp   # HTTPS
    $SUDO_CMD ufw allow 8000/tcp  # Laravel
    $SUDO_CMD ufw allow 8080/tcp  # Traefik
    $SUDO_CMD ufw allow 5432/tcp  # PostgreSQL
    $SUDO_CMD ufw allow 9200/tcp  # Elasticsearch
    $SUDO_CMD ufw allow 6379/tcp  # Redis
    $SUDO_CMD ufw allow 8500/tcp  # Consul
    
    print_success "ファイアウォール設定完了"
else
    print_warning "UFWがインストールされていません"
fi
echo ""

# Managerノードの設定
if [[ $NODE_TYPE == "manager" ]]; then
    print_info "Managerノードとして設定中..."
    
    # Swarm初期化
    docker swarm init --advertise-addr $NODE_IP
    
    # ネットワーク作成
    docker network create --driver=overlay --attachable traefik-public
    
    # Managerトークンの表示
    MANAGER_TOKEN=$(docker swarm join-token -q manager)
    WORKER_TOKEN=$(docker swarm join-token -q worker)
    
    print_success "Managerノードの設定完了！"
    echo ""
    print_info "Managerノード参加用トークン:"
    echo "  $MANAGER_TOKEN"
    echo ""
    print_info "Workerノード参加用トークン:"
    echo "  $WORKER_TOKEN"
    echo ""
    print_info "Workerノード参加コマンド:"
    echo "  docker swarm join --token $WORKER_TOKEN $NODE_IP:2377"
    echo ""
    print_info "次のステップ:"
    echo "1. Workerノードで上記コマンドを実行"
    echo "2. ./deploy-stack.sh でスタックをデプロイ"
    
# Workerノードの設定
elif [[ $NODE_TYPE == "worker" ]]; then
    print_info "Workerノードとして設定中..."
    
    if [[ $# -lt 3 ]]; then
        print_error "Workerノード設定にはManagerノードのIPとトークンが必要です"
        print_info "使用方法: $0 worker [NODE_IP] [MANAGER_IP] [WORKER_TOKEN]"
        exit 1
    fi
    
    MANAGER_IP=$3
    WORKER_TOKEN=$4
    
    # Swarmクラスタに参加
    docker swarm join --token $WORKER_TOKEN $MANAGER_IP:2377
    
    print_success "Workerノードの設定完了！"
    echo ""
    print_info "Managerノードで以下を実行してノードの確認:"
    echo "  docker node ls"
    
else
    print_error "無効なノードタイプです: $NODE_TYPE"
    print_info "有効な値: manager, worker"
    exit 1
fi

echo ""
print_success "Docker Swarmセットアップが完了しました！"
echo ""
print_info "現在の状態確認:"
docker node ls
echo ""
print_info "ネットワーク確認:"
docker network ls 