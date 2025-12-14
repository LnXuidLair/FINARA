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
            $table->foreignId('pembayaran_siswa_id')->nullable()->constrained('pembayaran_siswa')->nullOnDelete();
            $table->string('order_id')->unique();
            $table->integer('gross_amount');
            $table->string('snap_token')->nullable();
            $table->string('transaction_status')->default('pending');
            $table->string('payment_type')->nullable();
            $table->string('status_code')->nullable();
            $table->string('fraud_status')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamps();
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
