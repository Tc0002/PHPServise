<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録 - 製品管理システム</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
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
            padding: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
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
        }
        
        .btn-custom {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .input-group-text {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-right: none;
            color: #6b7280;
            font-weight: 600;
        }
        
        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        
        .btn-primary {
            background: #4f46e5;
            border: none;
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }
        
        .btn-secondary {
            background: #6b7280;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
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
                padding: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .btn-custom {
                width: 100%;
            }
        }
    </style>
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
        <div class="content-card">
            <div class="page-header">
                <h1 class="page-title">新規登録</h1>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-custom">
                                          登録一覧に戻る
                </a>
            </div>

            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="form-label required-field">製品名</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                                                        placeholder="製品名を入力してください" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label required-field">製品説明</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" 
                              placeholder="製品の詳細な説明を入力してください" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="price" class="form-label required-field">価格</label>
                            <div class="input-group">
                                <span class="input-group-text">¥</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" 
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="stock" class="form-label required-field">在庫数</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock') }}" 
                                   min="0" placeholder="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="category" class="form-label required-field">カテゴリ</label>
                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                           id="category" name="category" value="{{ old('category') }}" 
                           placeholder="例: 電子機器、衣類、食品など" required>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image_path" class="form-label">画像パス</label>
                    <input type="text" class="form-control @error('image_path') is-invalid @enderror" 
                           id="image_path" name="image_path" value="{{ old('image_path') }}"
                                                        placeholder="製品画像のURLまたはパスを入力してください">
                    @error('image_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            製品を有効にする
                        </label>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        製品を登録する
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 