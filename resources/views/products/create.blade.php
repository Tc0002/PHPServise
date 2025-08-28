@extends('layouts.app')

@section('title', '新規登録 - 製品管理システム')

@section('content')
    @include('components.page-header', [
        'title' => '新規登録',
        'actions' => '<a href="' . route('products.index') . '" class="btn btn-secondary">一覧に戻る</a>'
    ])

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 基本情報 -->
            <div class="space-y-4">
                <div class="form-group">
                    <label for="name" class="form-label">製品名 <span class="text-danger">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="barcode" class="form-label">バーコード</label>
                    <input type="text" 
                           id="barcode" 
                           name="barcode" 
                           value="{{ old('barcode') }}" 
                           class="form-control @error('barcode') is-invalid @enderror">
                    @error('barcode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">カテゴリ</label>
                    <input type="text" 
                           id="category" 
                           name="category" 
                           value="{{ old('category') }}" 
                           class="form-control @error('category') is-invalid @enderror">
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">説明</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              class="form-textarea @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 価格・在庫情報 -->
            <div class="space-y-4">
                <div class="form-group">
                    <label for="price" class="form-label">価格 <span class="text-danger">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">¥</span>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               value="{{ old('price') }}" 
                               class="form-control pl-8 @error('price') is-invalid @enderror" 
                               min="0" 
                               step="1" 
                               required>
                    </div>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock" class="form-label">在庫数 <span class="text-danger">*</span></label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           value="{{ old('stock', 0) }}" 
                           class="form-control @error('stock') is-invalid @enderror" 
                           min="0" 
                           required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_active" class="form-label">ステータス</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }} 
                                   class="mr-2">
                            <span>有効</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="is_active" 
                                   value="0" 
                                   {{ old('is_active') == '0' ? 'checked' : '' }} 
                                   class="mr-2">
                            <span>無効</span>
                        </label>
                    </div>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">画像</label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*" 
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">JPG, PNG, GIF形式で最大2MBまで</small>
                </div>
            </div>
        </div>

        <!-- 送信ボタン -->
        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">キャンセル</a>
            <button type="submit" class="btn btn-primary">登録する</button>
        </div>
    </form>
@endsection 