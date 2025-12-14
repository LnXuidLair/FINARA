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
        Schema::table('orangtua', function (Blueprint $table) {
            if (!Schema::hasColumn('orangtua', 'id_user')) {
                $table->foreignId('id_user')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orangtua', function (Blueprint $table) {
            if (Schema::hasColumn('orangtua', 'id_user')) {
                $table->dropConstrainedForeignId('id_user');
            }
        });
    }
};
