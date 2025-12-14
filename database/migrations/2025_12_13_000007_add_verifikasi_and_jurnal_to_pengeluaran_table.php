<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pengeluaran', 'status_verifikasi')) {
                $table->string('status_verifikasi')->default('pending')->after('keterangan');
            }

            if (!Schema::hasColumn('pengeluaran', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            }

            if (!Schema::hasColumn('pengeluaran', 'id_jurnal')) {
                $table->foreignId('id_jurnal')->nullable()->after('catatan_verifikasi')->constrained('jurnal_umum')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            if (Schema::hasColumn('pengeluaran', 'id_jurnal')) {
                $table->dropConstrainedForeignId('id_jurnal');
            }

            if (Schema::hasColumn('pengeluaran', 'catatan_verifikasi')) {
                $table->dropColumn('catatan_verifikasi');
            }

            if (Schema::hasColumn('pengeluaran', 'status_verifikasi')) {
                $table->dropColumn('status_verifikasi');
            }
        });
    }
};
