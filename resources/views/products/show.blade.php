@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- ヘッダー -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">製品詳細</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('products.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        一覧に戻る
                    </a>
                    <a href="{{ route('products.edit', $product->id) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        編集
                    </a>
                </div>
            </div>
        </div>

                    <!-- 製品情報カード -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- 製品画像 -->
            @if($product->image_path)
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-6xl text-gray-400 mb-2">📷</div>
                        <div class="text-xl font-bold text-gray-500">[NO IMAGE]</div>
                        <div class="text-sm text-gray-400 mt-1">画像が登録されていません</div>
                    </div>
                </div>
            @endif

            <!-- 製品詳細情報 -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- 基本情報 -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $product->name }}</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">製品名</label>
                                <p class="text-lg text-gray-900">{{ $product->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">カテゴリー</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $product->category }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">価格</label>
                                <p class="text-2xl font-bold text-green-600">¥{{ number_format($product->price) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">在庫数</label>
                                <p class="text-lg {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product->stock > 0 ? $product->stock . '個' : '在庫切れ' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 詳細説明 -->
                    <div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">製品説明</label>
                            <div class="bg-gray-50 rounded-lg p-4 min-h-[120px]">
                                <p class="text-gray-900 leading-relaxed">{{ $product->description }}</p>
                            </div>
                        </div>

                        <!-- 管理情報 -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">管理情報</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p>製品ID: {{ $product->id }}</p>
                                <p>作成日: {{ $product->created_at->format('Y年m月d日 H:i') }}</p>
                                <p>更新日: {{ $product->updated_at->format('Y年m月d日 H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- アクションボタン -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap gap-3">
                    <a href="{{ route('products.edit', $product->id) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                        製品を編集
                    </a>
                    
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" 
                          onsubmit="return confirm('この製品を削除しますか？この操作は取り消せません。')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                            製品を削除
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 