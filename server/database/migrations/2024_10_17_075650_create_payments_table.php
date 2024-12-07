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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); //Payment ID
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Foreign key to orders
            $table->string('payment_method'); // Payment method (e.g., credit card, PayPal)
            $table->string('transaction_id')->nullable(); // Transaction ID from the payment gateway
            $table->string('status'); // Payment status (e.g., completed, pending, failed)
            $table->decimal('amount', 10, 2); // Amount paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
