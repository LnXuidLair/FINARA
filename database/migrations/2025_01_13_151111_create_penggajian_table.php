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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('id_pegawai')->nullable()->constrained('pegawai')->onDelete('set null');
            $table->string('periode');
            $table->integer('jumlah_hari');
            $table->string('gaji_perhari');
            $table->integer('total_gaji');
            $table->string('status_penggajian');
            $table->date('tanggal');
            $table->foreignId('id_jurnal')->nullable()->constrained('jurnal_umum')->onDelete('set null');
            $table->integer('jumlah_kehadiran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
