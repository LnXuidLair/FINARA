<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_siswa_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_siswa_id')->constrained('pembayaran_siswa')->cascadeOnDelete();
            $table->foreignId('tagihan_siswa_id')->constrained('tagihan_siswa')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['pembayaran_siswa_id', 'tagihan_siswa_id'], 'tagihan_pembayaran_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_siswa_pembayaran');
    }
};
