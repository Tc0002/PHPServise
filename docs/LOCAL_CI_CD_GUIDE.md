# �� ローカル実行用CI/CDガイド

## �� 概要
このガイドでは、ローカル環境で実行されるLaravel Product SystemのCI/CDパイプラインについて説明します。

## �� ワークフローの流れ

### **1. コードプッシュ**
```bash
git add .
git commit -m "feat: 新機能の追加"
git push origin main
```

### **2. 自動実行**
- GitHub Actionsが自動的に実行
- コードの品質チェック
- テストの実行
- Dockerイメージのビルド

### **3. ローカルデプロイ**
```bash
# Dockerイメージのダウンロード
# GitHub ActionsのArtifactsからダウンロード

# ローカル環境での実行
docker-compose -f docker-compose.local.yml up -d
```

## 🔧 ローカル環境の設定

### **必要なソフトウェア**
- Docker Desktop
- Docker Compose
- Git

### **環境変数の設定**
```bash
# .env.localファイルをコピー
cp .env.local .env
```

### **サービスの起動**
```bash
# 依存サービスの起動
docker-compose -f docker-compose.local.yml up -d

# Laravelアプリケーションの起動
docker-compose up -d
```

## �� 監視とログ

### **サービスの状態確認**
```bash
# 全サービスの状態確認
docker-compose -f docker-compose.local.yml ps

# ログの確認
docker-compose -f docker-compose.local.yml logs -f
```

### **アプリケーションのアクセス**
- **Laravel App**: http://localhost:8000
- **MinIO Web UI**: http://localhost:9001
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379
- **Elasticsearch**: http://localhost:9200

## 🐛 トラブルシューティング

### **よくある問題**
1. **ポートの競合**: 他のサービスが同じポートを使用している
2. **メモリ不足**: Docker Desktopのメモリ設定を確認
3. **権限の問題**: ファイルの読み書き権限を確認

### **解決方法**
```bash
# サービスの再起動
docker-compose -f docker-compose.local.yml restart

# ログの確認
docker-compose -f docker-compose.local.yml logs [service-name]

# 完全リセット
docker-compose -f docker-compose.local.yml down -v
docker-compose -f docker-compose.local.yml up -d
```

## 📚 参考資料
- [GitHub Actions公式ドキュメント](https://docs.github.com/ja/actions)
- [Docker Compose公式ドキュメント](https://docs.docker.com/compose/)
- [Laravel公式ドキュメント](https://laravel.com/docs)
