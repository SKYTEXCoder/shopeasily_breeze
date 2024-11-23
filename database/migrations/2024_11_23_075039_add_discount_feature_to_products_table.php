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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('price', 'final_price');
            $table->decimal('original_price', 15, 2)->nullable()->after('final_price');
            $table->unsignedTinyInteger('discount_percentage')->default(0)->after('original_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('final_price', 'price');
            $table->dropColumn(['original_price', 'discount_percentage']);
        });
    }
};
