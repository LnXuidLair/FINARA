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
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis', ['operasional', 'gaji']);
            $table->foreignId('referensi_penggajian_id')->nullable()->constrained('penggajian')->nullOnDelete();
            $table->foreignId('coa_debit_id')->constrained('coa')->restrictOnDelete();
            $table->foreignId('coa_kredit_id')->constrained('coa')->restrictOnDelete();
            $table->integer('nominal');
            $table->string('keterangan');
            $table->timestamps();

            $table->unique('referensi_penggajian_id');
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
