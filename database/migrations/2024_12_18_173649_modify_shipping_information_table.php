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
        Schema::table('shipping_information', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('user_id');
            $table->string('label')->nullable()->after('is_primary');
            $table->string('province')->nullable()->after('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_information', function (Blueprint $table) {
            //
        });
    }
};
