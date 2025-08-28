@extends('layouts.app')

@section('title', 'Laravel Product System - 製品管理システム')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">製品管理システムへようこそ</h1>
        <p class="hero-subtitle">
            最新のLaravel 12.25.0で構築された、高性能で使いやすい製品管理システムです。
            製品の登録、編集、削除を簡単に行えます。
        </p>
        <div class="cta-buttons">
            <a href="/products" class="btn btn-primary">登録一覧を見る</a>
            <a href="/products/create" class="btn btn-secondary">新規登録</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <h2 style="text-align: center; font-size: 2rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">
            主要機能
        </h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);"></div>
                <h3 class="feature-title">製品管理</h3>
                <p class="feature-description">
                    製品の登録、編集、削除を簡単に行えます。画像アップロードや詳細情報の管理も可能です。
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);"></div>
                <h3 class="feature-title">検索・フィルタ</h3>
                <p class="feature-description">
                    製品名、カテゴリ、価格などで簡単に検索できます。高度なフィルタリング機能も搭載。
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);"></div>
                <h3 class="feature-title">データ分析</h3>
                <p class="feature-description">
                    製品の売上データや在庫状況をリアルタイムで確認できます。グラフやレポートも充実。
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);"></div>
                <h3 class="feature-title">セキュリティ</h3>
                <p class="feature-description">
                    最新のセキュリティ対策を実装。ユーザー認証と権限管理で安全な運用をサポート。
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);"></div>
                <h3 class="feature-title">レスポンシブ対応</h3>
                <p class="feature-description">
                    PC、タブレット、スマートフォンなど、あらゆるデバイスで快適に使用できます。
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #a8edea, #fed6e3);"></div>
                <h3 class="feature-title">高速パフォーマンス</h3>
                <p class="feature-description">
                    Laravel 12の最新機能を活用し、高速で安定した動作を実現しています。
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Laravel Product System. All rights reserved.</p>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">
            Built with using Laravel 12.25.0 & PHP 8.4.11
        </p>
    </footer>
@endsection
