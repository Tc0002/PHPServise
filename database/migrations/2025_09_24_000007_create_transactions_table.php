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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('security_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('execution_id')->nullable()->constrained('executions')->nullOnDelete();
            $table->string('type', 32); // trade | deposit | withdrawal | dividend | interest | fee | tax | transfer
            $table->decimal('amount', 18, 4);
            $table->string('currency', 3)->default('JPY');
            $table->text('description')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['account_id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

