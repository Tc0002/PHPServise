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
        Schema::create('holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('security_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 24, 8)->default(0);
            $table->decimal('average_cost', 18, 4)->nullable();
            $table->string('cost_currency', 3)->default('JPY');
            $table->timestamps();

            $table->unique(['account_id', 'security_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holdings');
    }
};

