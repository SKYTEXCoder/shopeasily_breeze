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
        Schema::create('wishlist_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_id')->constrained('wishlists')->cascadeOnDelete(); // Foreign key to wishlists
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete(); // Foreign key to products
            $table->integer('priority')->default(1); // For example, 1 = Low, 2 = Medium, 3 = High
            $table->timestamp('added_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_products');
    }
};
