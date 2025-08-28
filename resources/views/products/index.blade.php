<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>製品管理システム - 登録一覧</title>
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
</head>
<body>
    @include('components.navigation')

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-card">
            @include('components.page-header', [
                'title' => '登録一覧',
                'actions' => '<a href="' . route('products.create') . '" class="btn btn-primary">新規登録</a>'
            ])

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 総件数表示 -->
            <div class="text-muted mb-3">
                <small>総件数: {{ $products->total() }}件</small>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                @php
                                    $nextDirection = ($sortField === 'id' && $sortDirection === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => $nextDirection]) }}" 
                                   class="sort-link {{ $sortField === 'id' ? 'active' : '' }}">
                                    ID
                                    @if($sortField === 'id')
                                        <span class="sort-icon">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th>
                                @php
                                    $nextDirection = ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $nextDirection]) }}" 
                                   class="sort-link {{ $sortField === 'name' ? 'active' : '' }}">
                                    製品名
                                    @if($sortField === 'name')
                                        <span class="sort-icon">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th>
                                @php
                                    $nextDirection = ($sortField === 'category' && $sortDirection === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => $nextDirection]) }}" 
                                   class="sort-link {{ $sortField === 'category' ? 'active' : '' }}">
                                    カテゴリ
                                    @if($sortField === 'category')
                                        <span class="sort-icon">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th>
                                @php
                                    $nextDirection = ($sortField === 'price' && $sortDirection === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => $nextDirection]) }}" 
                                   class="sort-link {{ $sortField === 'price' ? 'active' : '' }}">
                                    価格
                                    @if($sortField === 'price')
                                        <span class="sort-icon">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th>
                                @php
                                    $nextDirection = ($sortField === 'stock' && $sortDirection === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => $nextDirection]) }}" 
                                   class="sort-link {{ $sortField === 'stock' ? 'active' : '' }}">
                                    在庫
                                    @if($sortField === 'stock')
                                        <span class="sort-icon">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
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
                                <td><span class="text-success">¥{{ number_format($product->price) }}</span></td>
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
                    {!! $products->links('vendor.pagination.simple-bootstrap-4') !!}
                </div>
            @endif
        </div>
    </div>
</body>
</html> 