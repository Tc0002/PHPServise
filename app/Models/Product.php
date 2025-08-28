<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'barcode',
        'description',
        'price',
        'stock',
        'category',
        'image_path',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean'
    ];

    // アクティブな商品のみを取得
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // 在庫がある商品のみを取得
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
