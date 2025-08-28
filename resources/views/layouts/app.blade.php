<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Laravel Product System - 製品管理システム')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
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
                position: sticky;
                top: 0;
                z-index: 1000;
                padding: 0.5rem 0;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }
            
            .navbar-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative;
            }
            
            /* ナビゲーションバーの検索窓 */
            .navbar-search {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                min-width: 250px;
                max-width: 500px;
                width: 100%;
            }
            
            .navbar-search-form {
                width: 100%;
            }
            
            .search-input-group {
                display: flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 2rem;
                border: 1px solid rgba(79, 70, 229, 0.2);
                overflow: hidden;
                transition: all 0.3s ease;
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }
            
            .search-input-group:focus-within {
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
                background: rgba(255, 255, 255, 1);
            }
            
            .navbar-search-input {
                width: 100%;
                border: none;
                background: transparent;
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
                outline: none;
                color: #374151;
            }
            
            .navbar-search-input::placeholder {
                color: #9ca3af;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
                font-weight: 700;
                color: #4f46e5;
                text-decoration: none;
                z-index: 1;
            }
            
            .navbar-nav {
                display: flex;
                list-style: none;
                gap: 1.5rem;
                margin: 0;
                padding: 0;
                align-items: center;
                z-index: 1;
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
            
            .navbar-nav a.active {
                color: #4f46e5;
                background: rgba(79, 70, 229, 0.1);
            }
            
            .main-content {
                min-height: calc(100vh - 70px);
                padding: 2rem 0;
            }
            
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
            }
            
            .content-card {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 1rem;
                padding: 2rem;
                backdrop-filter: blur(10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }
            
            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 2px solid #e5e7eb;
            }
            
            .page-title {
                color: #1f2937;
                margin: 0;
                font-size: 2rem;
                font-weight: 700;
            }
            
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.2s ease;
                border: none;
                cursor: pointer;
                font-size: 0.875rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
                border-radius: 0.375rem;
            }
            
            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                border-radius: 0.5rem;
            }
            
            .btn-primary {
                background-color: #4f46e5;
                color: white;
            }
            
            .btn-primary:hover {
                background-color: #4338ca;
            }
            
            .btn-secondary {
                background-color: #6b7280;
                color: white;
            }
            
            .btn-secondary:hover {
                background-color: #4b5563;
            }
            
            .btn-danger {
                background-color: #dc2626;
                color: white;
            }
            
            .btn-danger:hover {
                background-color: #b91c1c;
            }
            
            .btn-info {
                background-color: #0ea5e9;
                color: white;
            }
            
            .btn-info:hover {
                background-color: #0284c7;
            }
            
            .btn-warning {
                background-color: #f59e0b;
                color: white;
            }
            
            .btn-warning:hover {
                background-color: #d97706;
            }
            
            .btn-success {
                background-color: #059669;
                color: white;
            }
            
            .btn-success:hover {
                background-color: #047857;
            }
            
            .btn-group {
                display: inline-flex;
                gap: 0.25rem;
            }
            
            .btn-group .btn {
                border-radius: 0.375rem;
            }
            
            .btn-group .btn:first-child {
                border-top-left-radius: 0.375rem;
                border-bottom-left-radius: 0.375rem;
            }
            
            .btn-group .btn:last-child {
                border-top-right-radius: 0.375rem;
                border-bottom-right-radius: 0.375rem;
            }
            
            .card {
                background: white;
                border-radius: 0.75rem;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                overflow: hidden;
            }
            
            .alert {
                padding: 1rem;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
            }
            
            .alert-success {
                background-color: #d1fae5;
                color: #065f46;
                border: 1px solid #a7f3d0;
            }
            
            .alert-error {
                background-color: #fee2e2;
                color: #991b1b;
                border: 1px solid #fecaca;
            }
            
            .form-group {
                margin-bottom: 1rem;
            }
            
            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: #374151;
            }
            
            .form-control {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                transition: border-color 0.2s ease;
            }
            
            .form-control:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
            
            .form-input {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                transition: border-color 0.2s ease;
            }
            
            .form-input:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
            
            .form-textarea {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                min-height: 100px;
                resize: vertical;
                transition: border-color 0.2s ease;
            }
            
            .form-textarea:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
            
            .grid {
                display: grid;
                gap: 1.5rem;
            }
            
            .grid-cols-1 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
            
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            
            .grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            
            .grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
            
            /* テーブルスタイル */
            .table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 1rem;
            }
            
            .table th,
            .table td {
                padding: 0.75rem;
                text-align: left;
                border-bottom: 1px solid #e5e7eb;
                vertical-align: middle;
            }
            
            .table th {
                background-color: #f9fafb;
                font-weight: 600;
                color: #374151;
            }
            
            .table tbody tr:hover {
                background-color: #f9fafb;
            }
            
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            /* ソートリンク */
            .sort-link {
                color: #374151;
                text-decoration: none;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.2s ease;
            }
            
            .sort-link:hover {
                color: #4f46e5;
                text-decoration: none;
            }
            
            .sort-link.active {
                color: #4f46e5;
            }
            
            .sort-icon {
                font-size: 0.875rem;
                font-weight: bold;
                color: #4f46e5;
            }
            
            /* 検索セクション */
            .search-section {
                background: rgba(249, 250, 251, 0.8);
                border-radius: 0.75rem;
                padding: 1.5rem;
                border: 1px solid #e5e7eb;
            }
            
            .search-form .input-group {
                display: flex;
                gap: 0.5rem;
                align-items: center;
            }
            
            .search-input {
                flex: 1;
                padding: 0.75rem 1rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                font-size: 1rem;
                transition: border-color 0.2s ease;
            }
            
            .search-input:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
            
            .search-btn {
                padding: 0.75rem 1.5rem;
                white-space: nowrap;
            }
            
            .clear-btn {
                padding: 0.75rem 1.5rem;
                white-space: nowrap;
            }
            
            /* バーコード表示 */
            .barcode-display {
                background-color: #f3f4f6;
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                font-family: 'Courier New', monospace;
                font-size: 0.875rem;
                color: #374151;
                border: 1px solid #d1d5db;
            }
            
            /* テキストカラー */
            .text-muted {
                color: #6b7280 !important;
            }
            
            .text-success {
                color: #059669 !important;
            }
            
            .text-warning {
                color: #f59e0b !important;
            }
            
            .text-danger {
                color: #dc2626 !important;
            }
            
            .text-info {
                color: #0ea5e9 !important;
            }
            
            /* バッジ */
            .badge {
                display: inline-block;
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
                font-weight: 500;
                line-height: 1;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: 0.375rem;
            }
            
            .bg-success {
                background-color: #059669 !important;
                color: white !important;
            }
            
            .bg-secondary {
                background-color: #6b7280 !important;
                color: white !important;
            }
            
            /* ユーティリティクラス */
            .mb-3 { margin-bottom: 0.75rem !important; }
            .mb-4 { margin-bottom: 1rem !important; }
            .mt-4 { margin-top: 1rem !important; }
            .mt-8 { margin-top: 2rem !important; }
            .ms-2 { margin-left: 0.5rem !important; }
            .py-5 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }
            .pt-6 { padding-top: 1.5rem !important; }
            .d-flex { display: flex !important; }
            .d-inline { display: inline !important; }
            .justify-content-center { justify-content: center !important; }
            .justify-end { justify-content: flex-end !important; }
            .text-center { text-align: center !important; }
            .list-disc { list-style-type: disc !important; }
            .list-inside { list-style-position: inside !important; }
            
            /* ホームページ用スタイル */
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
            
            /* フォーム用スタイル */
            .space-y-4 > * + * {
                margin-top: 1rem;
            }
            
            .space-y-6 > * + * {
                margin-top: 1.5rem;
            }
            
            .grid {
                display: grid;
                gap: 1.5rem;
            }
            
            .grid-cols-1 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
            
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            
            .gap-6 {
                gap: 1.5rem;
            }
            
            .border-t {
                border-top: 1px solid #e5e7eb;
            }
            
            .border-gray-200 {
                border-color: #e5e7eb;
            }
            
            .text-gray-500 {
                color: #6b7280;
            }
            
            .pl-8 {
                padding-left: 2rem;
            }
            
            .mr-2 {
                margin-right: 0.5rem;
            }
            
            .space-x-4 > * + * {
                margin-left: 1rem;
            }
            
            .flex {
                display: flex;
            }
            
            .items-center {
                align-items: center;
            }
            
            .relative {
                position: relative;
            }
            
            .absolute {
                position: absolute;
            }
            
            .left-3 {
                left: 0.75rem;
            }
            
            .top-1/2 {
                top: 50%;
            }
            
            .transform {
                transform: translateY(-50%);
            }
            
            .-translate-y-1/2 {
                transform: translateY(-50%);
            }
            
            @media (max-width: 768px) {
                .grid-cols-2,
                .grid-cols-3,
                .grid-cols-4 {
                    grid-template-columns: repeat(1, minmax(0, 1fr));
                }
                
                .navbar-container {
                    flex-direction: column;
                    gap: 1rem;
                    padding: 1rem;
                    position: relative;
                }
                
                .navbar-search {
                    position: static;
                    left: auto;
                    top: auto;
                    transform: none;
                    order: 2;
                    max-width: 100%;
                    width: 100%;
                }
                
                .search-input-group {
                    max-width: 100%;
                }
                
                .navbar-nav {
                    order: 3;
                    flex-direction: row;
                    gap: 1rem;
                    justify-content: center;
                    z-index: 1;
                }
                
                .container {
                    padding: 0 1rem;
                }
                
                .page-header {
                    flex-direction: column;
                    gap: 1rem;
                    align-items: flex-start;
                }
                
                .btn {
                    width: 100%;
                    justify-content: center;
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
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-error">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Page Content -->
            <div class="content-card">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Laravel Product System. All rights reserved.</p>
                <p class="text-gray-400 text-sm mt-2">Built with Laravel {{ app()->version() }}</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html> 