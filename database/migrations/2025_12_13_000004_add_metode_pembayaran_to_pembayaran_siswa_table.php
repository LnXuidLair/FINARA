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
        Schema::table('pembayaran_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran_siswa', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('status_pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran_siswa', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }
        });
    }
};
