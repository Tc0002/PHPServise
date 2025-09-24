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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('security_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 18, 4);
            $table->string('currency', 3)->default('JPY');
            $table->timestamp('as_of');
            $table->timestamps();

            $table->unique(['security_id', 'as_of']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};

