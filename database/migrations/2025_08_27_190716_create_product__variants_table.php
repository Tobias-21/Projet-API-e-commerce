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
        Schema::create('product__variants', function (Blueprint $table) {
            $table->id();
             $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->json('attributes');
            $table->decimal('price', 8, 2);
            $table->string('sku')->unique();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product__variants');
    }
};
