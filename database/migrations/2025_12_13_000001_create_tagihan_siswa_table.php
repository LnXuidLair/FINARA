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
        Schema::create('tagihan_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('orangtua_id')->constrained('orangtua')->cascadeOnDelete();
            $table->string('jenis_tagihan', 100);
            $table->string('periode', 20);
            $table->integer('nominal');
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_siswa');
    }
};
