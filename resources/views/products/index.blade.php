<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品管理システム</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>商品一覧</h1>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">新規商品登録</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>商品名</th>
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
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category }}</td>
                                        <td>¥{{ number_format($product->price) }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">有効</span>
                                            @else
                                                <span class="badge bg-secondary">無効</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info">詳細</a>
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">編集</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">商品が登録されていません</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 