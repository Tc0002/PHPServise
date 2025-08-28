<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>製品管理システム - 登録一覧</title>
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
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
            max-width: 1200px;
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
        
        .table {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .table thead th {
            background: #f8fafc;
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: #374151;
        }
        
        .table tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .table tbody tr:hover {
            background: #f8fafc;
        }
        
        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            margin: 0.25rem;
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
    @include('components.navigation')

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-card">
            <div class="page-header">
                <h1 class="page-title">登録一覧</h1>
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-custom">
                                          新規登録
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>製品名</th>
                            <th>カテゴリ</th>
                            <th>価格</th>
                            <th>在庫</th>
                            <th>ステータス</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td><strong>#{{ $product->id }}</strong></td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->category }}</td>
                                <td><span class="text-success fw-bold">¥{{ number_format($product->price) }}</span></td>
                                <td>
                                    @if($product->stock > 10)
                                        <span class="text-success">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="text-warning">{{ $product->stock }}</span>
                                    @else
                                        <span class="text-danger">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">有効</span>
                                    @else
                                        <span class="badge bg-secondary">無効</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info">
                                            詳細
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                            編集
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('本当に削除しますか？この操作は取り消せません。')">
                                                削除
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                                                <h5>製品が登録されていません</h5>
                        <p>最初の製品を登録してみましょう！</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            製品を登録する
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 