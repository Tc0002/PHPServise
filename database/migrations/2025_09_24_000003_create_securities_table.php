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
        Schema::create('securities', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('name');
            $table->string('exchange')->nullable();
            $table->string('currency', 3)->default('JPY');
            $table->string('type')->default('equity'); // equity, etf, bond, crypto, etc
            $table->string('isin')->nullable();
            $table->string('cusip')->nullable();
            $table->string('sector')->nullable();
            $table->string('industry')->nullable();
            $table->timestamps();

            $table->unique(['symbol', 'exchange']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('securities');
    }
};

