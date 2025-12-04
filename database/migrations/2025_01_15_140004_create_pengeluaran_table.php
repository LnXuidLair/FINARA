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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('id_penggajian')->nullable()->constrained('penggajian')->onDelete('set null');
            $table->string('kategori');
            $table->string('deskripsi');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->string('bukti_pembayaran');
            $table->foreignId('id_jurnal')->nullable()->constrained('jurnal_umum')->onDelete('set null');
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
