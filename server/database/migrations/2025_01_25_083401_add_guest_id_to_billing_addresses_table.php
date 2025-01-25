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
        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->uuid('guest_id')->nullable(); // Add guest_id column for non-authenticated users
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->dropColumn('guest_id');
        });
    }
};
