<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Product System - 製品管理システム</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                line-height: 1.6;
                color: #1f2937;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            
            .navbar {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                padding: 0.5rem 0;
            }
            
            .navbar-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
                font-weight: 700;
                color: #4f46e5;
                text-decoration: none;
            }
            
            .navbar-nav {
                display: flex;
                list-style: none;
                gap: 1.5rem;
                margin: 0;
                padding: 0;
                flex-direction: row;
                align-items: center;
            }
            
            .navbar-nav li {
                margin: 0;
                padding: 0;
            }
            
            .navbar-nav a {
                color: #6b7280;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                padding: 0.4rem 0.8rem;
                border-radius: 0.5rem;
                font-size: 0.9rem;
                white-space: nowrap;
            }
            
            .navbar-nav a:hover {
                color: #4f46e5;
                background: rgba(79, 70, 229, 0.1);
            }
            
            .main-content {
                margin-top: 70px;
                padding: 4rem 2rem;
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .hero-section {
                text-align: center;
                margin-bottom: 4rem;
            }
            
            .hero-title {
                font-size: 3.5rem;
                font-weight: 700;
                color: white;
                margin-bottom: 1rem;
                text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            
            .hero-subtitle {
                font-size: 1.25rem;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 2rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .cta-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .btn {
                display: inline-block;
                padding: 1rem 2rem;
                border-radius: 0.75rem;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 1rem;
                min-width: 200px;
                text-align: center;
            }
            
            .btn-primary {
                background: #1e40af;
                color: white;
                border: 2px solid #1e40af;
                box-shadow: 0 4px 6px rgba(30, 64, 175, 0.3);
            }
            
            .btn-primary:hover {
                background: #1e3a8a;
                border-color: #1e3a8a;
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(30, 64, 175, 0.4);
            }
            
            .btn-secondary {
                background: rgba(255, 255, 255, 0.9);
                color: #1e40af;
                border: 2px solid rgba(255, 255, 255, 0.8);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            
            .btn-secondary:hover {
                background: white;
                color: #1e3a8a;
                border-color: white;
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }
            
            .features-section {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 1.5rem;
                padding: 3rem;
                margin-bottom: 3rem;
                backdrop-filter: blur(10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                margin-top: 2rem;
            }
            
            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
                border: 1px solid rgba(0, 0, 0, 0.05);
            }
            
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }
            
            .feature-icon {
                width: 3rem;
                height: 3rem;
                background: linear-gradient(135deg, #667eea, #764ba2);
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
                color: white;
                font-size: 1.5rem;
            }
            
            .feature-title {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: #1f2937;
            }
            
            .feature-description {
                color: #6b7280;
                line-height: 1.6;
            }
            
            .footer {
                background: rgba(0, 0, 0, 0.1);
                color: rgba(255, 255, 255, 0.8);
                text-align: center;
                padding: 2rem;
                margin-top: 4rem;
                border-radius: 1rem 1rem 0 0;
            }
            
            @media (max-width: 768px) {
                .navbar-container {
                    padding: 0 1rem;
                    flex-direction: column;
                    gap: 1rem;
                }
                
                .navbar-nav {
                    gap: 1rem;
                    flex-wrap: wrap;
                    justify-content: center;
                }
                
                .main-content {
                    padding: 2rem 1rem;
                }
                
                .hero-title {
                    font-size: 2.5rem;
                }
                
                .features-section {
                    padding: 2rem 1rem;
                }
                
                .cta-buttons {
                    flex-direction: column;
                    align-items: center;
                }
                
                .btn {
                    width: 100%;
                    max-width: 300px;
                    text-align: center;
                }
            }
        </style>
    @endif
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">
                Laravel Product System
            </a>
            <ul class="navbar-nav">
                <li><a href="/">ホーム</a></li>
                <li><a href="/products">製品一覧</a></li>
                <li><a href="/products/create">製品作成</a></li>
                @if (Route::has('login'))
                    @auth
                        <li><a href="{{ url('/dashboard') }}">ダッシュボード</a></li>
                    @else
                        <li><a href="{{ route('login') }}">ログイン</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">登録</a></li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <h1 class="hero-title">製品管理システムへようこそ</h1>
            <p class="hero-subtitle">
                最新のLaravel 12.25.0で構築された、高性能で使いやすい製品管理システムです。
                製品の登録、編集、削除を簡単に行えます。
            </p>
            <div class="cta-buttons">
                <a href="/products" class="btn btn-primary">製品一覧を見る</a>
                <a href="/products/create" class="btn btn-secondary">新規製品を登録</a>
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
</body>
</html>
