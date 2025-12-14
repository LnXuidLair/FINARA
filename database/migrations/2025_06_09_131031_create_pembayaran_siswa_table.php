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
        Schema::create('pembayaran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_siswa')->nullable()->constrained('siswa')->onDelete('set null');
            $table->string('jenis_pembayaran');
            $table->date('tanggal_bayar');
            $table->integer('jumlah');
            $table->string('status_pembayaran');
            $table->string('order_id')->nullable()->unique();
            $table->string('snap_token')->nullable();
            $table->foreignId('id_jurnal')->nullable()->constrained('jurnal_umum')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_siswa');
    }
};
