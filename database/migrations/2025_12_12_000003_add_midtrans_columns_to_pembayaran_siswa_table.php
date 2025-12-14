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
            if (!Schema::hasColumn('pembayaran_siswa', 'order_id')) {
                $table->string('order_id')->nullable()->unique()->after('status_pembayaran');
            }
            if (!Schema::hasColumn('pembayaran_siswa', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran_siswa', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
            if (Schema::hasColumn('pembayaran_siswa', 'order_id')) {
                $table->dropUnique(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};
