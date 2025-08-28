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
                background-color: #f9fafb;
                min-height: 100vh;
            }
            
            .navbar {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
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
                align-items: center;
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
            
            @media (max-width: 768px) {
                .grid-cols-2,
                .grid-cols-3,
                .grid-cols-4 {
                    grid-template-columns: repeat(1, minmax(0, 1fr));
                }
                
                .navbar-nav {
                    flex-direction: column;
                    gap: 0.5rem;
                }
                
                .container {
                    padding: 0 1rem;
                }
            }
        </style>
    @endif
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('products.index') }}" class="navbar-brand">
                🏪 Laravel Product System
            </a>
            <ul class="navbar-nav">
                <li><a href="/" class="{{ request()->routeIs('welcome') ? 'active' : '' }}">ホーム</a></li>
                <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active' : '' }}">登録一覧</a></li>
                <li><a href="{{ route('products.create') }}" class="{{ request()->routeIs('products.create') ? 'active' : '' }}">新規登録</a></li>
            </ul>
        </div>
    </nav>

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
            @yield('content')
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