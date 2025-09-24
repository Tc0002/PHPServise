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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('security_id')->constrained()->cascadeOnDelete();
            $table->string('side', 8)->default('buy'); // buy | sell
            $table->string('order_type', 16)->default('market'); // market | limit | stop | stop_limit
            $table->decimal('quantity', 24, 8);
            $table->decimal('limit_price', 18, 4)->nullable();
            $table->decimal('stop_price', 18, 4)->nullable();
            $table->string('status', 16)->default('pending'); // pending | filled | partial | cancelled | rejected
            $table->string('external_order_id')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['account_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

