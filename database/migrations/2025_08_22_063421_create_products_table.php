<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // 商品名
            $table->text('description');      // 商品説明
            $table->decimal('price', 10, 2);  // 価格（小数点2桁）
            $table->integer('stock');         // 在庫数
            $table->string('category');       // カテゴリ
            $table->string('image_path')->nullable(); // 画像パス（オプション）
            $table->boolean('is_active')->default(true); // 有効/無効
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
