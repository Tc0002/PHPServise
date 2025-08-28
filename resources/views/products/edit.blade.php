@extends('layouts.app')

@section('title', '製品編集 - ' . $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- ヘッダー -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">製品編集</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('products.show', $product->id) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        詳細に戻る
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        一覧に戻る
                    </a>
                </div>
            </div>
        </div>

        <!-- 編集フォーム -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- 左カラム：基本情報 -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">基本情報</h2>
                            
                            <!-- 製品名 -->
                            <div class="form-group">
                                <label for="name" class="form-label">製品名 <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}" 
                                       class="form-input @error('name') border-red-500 @enderror" 
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- バーコード -->
                            <div class="form-group">
                                <label for="barcode" class="form-label">バーコード</label>
                                <input type="text" 
                                       id="barcode" 
                                       name="barcode" 
                                       value="{{ old('barcode', $product->barcode) }}" 
                                       class="form-input @error('barcode') border-red-500 @enderror"
                                       placeholder="バーコードを入力してください（オプション）"
                                       autocomplete="off">
                                <p class="text-gray-500 text-sm mt-1">バーコードリーダーで読み取り可能</p>
                                @error('barcode')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- カテゴリー -->
                            <div class="form-group">
                                <label for="category" class="form-label">カテゴリー <span class="text-red-500">*</span></label>
                                <select id="category" 
                                        name="category" 
                                        class="form-input @error('category') border-red-500 @enderror" 
                                        required>
                                    <option value="">カテゴリーを選択</option>
                                    <option value="電子機器" {{ old('category', $product->category) == '電子機器' ? 'selected' : '' }}>電子機器</option>
                                    <option value="衣類" {{ old('category', $product->category) == '衣類' ? 'selected' : '' }}>衣類</option>
                                    <option value="食品" {{ old('category', $product->category) == '食品' ? 'selected' : '' }}>食品</option>
                                    <option value="書籍" {{ old('category', $product->category) == '書籍' ? 'selected' : '' }}>書籍</option>
                                    <option value="スポーツ" {{ old('category', $product->category) == 'スポーツ' ? 'selected' : '' }}>スポーツ</option>
                                    <option value="その他" {{ old('category', $product->category) == 'その他' ? 'selected' : '' }}>その他</option>
                                </select>
                                @error('category')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 価格 -->
                            <div class="form-group">
                                <label for="price" class="form-label">価格 <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">¥</span>
                                    <input type="number" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $product->price) }}" 
                                           class="form-input pl-8 @error('price') border-red-500 @enderror" 
                                           min="0" 
                                           step="1" 
                                           required>
                                </div>
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 在庫数 -->
                            <div class="form-group">
                                <label for="stock" class="form-label">在庫数 <span class="text-red-500">*</span></label>
                                <input type="number" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock', $product->stock) }}" 
                                       class="form-input @error('stock') border-red-500 @enderror" 
                                       min="0" 
                                       required>
                                @error('stock')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- 右カラム：詳細情報 -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">詳細情報</h2>
                            
                            <!-- 製品説明 -->
                            <div class="form-group">
                                <label for="description" class="form-label">製品説明 <span class="text-red-500">*</span></label>
                                <textarea id="description" 
                                          name="description" 
                                          class="form-textarea @error('description') border-red-500 @enderror" 
                                          rows="6" 
                                          required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 画像パス -->
                            <div class="form-group">
                                <label for="image_path" class="form-label">画像パス</label>
                                <input type="text" 
                                       id="image_path" 
                                       name="image_path" 
                                       value="{{ old('image_path', $product->image_path) }}" 
                                       class="form-input @error('image_path') border-red-500 @enderror" 
                                       placeholder="例: products/image.jpg">
                                @error('image_path')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">画像ファイルのパスを入力してください（オプション）</p>
                            </div>

                            <!-- 現在の画像プレビュー -->
                            <div class="form-group">
                                <label class="form-label">現在の画像</label>
                                @if($product->image_path)
                                    <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $product->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-4xl text-gray-400 mb-1">📷</div>
                                            <div class="text-lg font-bold text-gray-500">[NO IMAGE]</div>
                                            <div class="text-xs text-gray-400">画像が登録されていません</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 管理情報表示 -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">管理情報</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">製品ID:</span> {{ $product->id }}
                            </div>
                            <div>
                                <span class="font-medium">作成日:</span> {{ $product->created_at->format('Y年m月d日 H:i') }}
                            </div>
                            <div>
                                <span class="font-medium">更新日:</span> {{ $product->updated_at->format('Y年m月d日 H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- アクションボタン -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap gap-3">
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                            💾 更新を保存
                        </button>
                        
                        <a href="{{ route('products.show', $product->id) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                            ❌ キャンセル
                        </a>
                        
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" 
                              onsubmit="return confirm('この製品を削除しますか？この操作は取り消せません。')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                                🗑️ 製品を削除
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// フォーム送信前の確認
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const price = document.getElementById('price').value;
    const stock = document.getElementById('stock').value;
    const description = document.getElementById('description').value.trim();
    const category = document.getElementById('category').value;
    
    if (!name || !price || !stock || !description || !category) {
        e.preventDefault();
        alert('必須項目をすべて入力してください。');
        return false;
    }
    
    if (price < 0) {
        e.preventDefault();
        alert('価格は0以上で入力してください。');
        return false;
    }
    
    if (stock < 0) {
        e.preventDefault();
        alert('在庫数は0以上で入力してください。');
        return false;
    }
    
            return confirm('製品情報を更新しますか？');
});

// 価格の自動フォーマット
document.getElementById('price').addEventListener('blur', function() {
    const value = parseInt(this.value);
    if (!isNaN(value) && value >= 0) {
        this.value = value;
    }
});

// 在庫数の自動フォーマット
document.getElementById('stock').addEventListener('blur', function() {
    const value = parseInt(this.value);
    if (!isNaN(value) && value >= 0) {
        this.value = value;
    }
});
</script>
@endpush 