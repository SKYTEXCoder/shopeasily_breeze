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
        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
            $table->boolean('is_admin')->default(false)->after('remember_token')->comment('0 = user, 1 = admin');
            $table->string('image')->nullable()->after('is_admin');
            $table->string('first_name')->nullable()->after('name')->comment('First Name');
            $table->string('last_name')->nullable()->after('first_name')->comment('Last Name');
            $table->string('phone_number', 15)->nullable()->unique()->after('email_verified_at')->comment('Phone Number');
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropColumn(['is_admin', 'image']);
            $table->dropColumn(['first_name', 'last_name']);
            $table->dropColumn('phone_number');
            $table->dropIndex('phone_number');
        });
    }
};
