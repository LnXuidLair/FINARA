<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pengeluaran', 'jenis')) {
                $table->enum('jenis', ['operasional', 'gaji'])->default('operasional')->after('tanggal');
            }

            if (!Schema::hasColumn('pengeluaran', 'referensi_penggajian_id')) {
                $table->unsignedBigInteger('referensi_penggajian_id')->nullable()->after('jenis');
            }

            if (!Schema::hasColumn('pengeluaran', 'coa_debit_id')) {
                $table->unsignedBigInteger('coa_debit_id')->nullable()->after('referensi_penggajian_id');
            }

            if (!Schema::hasColumn('pengeluaran', 'coa_kredit_id')) {
                $table->unsignedBigInteger('coa_kredit_id')->nullable()->after('coa_debit_id');
            }

            if (!Schema::hasColumn('pengeluaran', 'nominal')) {
                $table->integer('nominal')->nullable()->after('coa_kredit_id');
            }

            if (!Schema::hasColumn('pengeluaran', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('nominal');
            }
        });

        if (Schema::hasColumn('pengeluaran', 'jumlah') && Schema::hasColumn('pengeluaran', 'nominal')) {
            DB::table('pengeluaran')
                ->whereNull('nominal')
                ->update(['nominal' => DB::raw('jumlah')]);
        }

        if (Schema::hasColumn('pengeluaran', 'deskripsi') && Schema::hasColumn('pengeluaran', 'keterangan')) {
            DB::table('pengeluaran')
                ->whereNull('keterangan')
                ->update(['keterangan' => DB::raw('deskripsi')]);
        }

        if (Schema::hasColumn('pengeluaran', 'id_penggajian') && Schema::hasColumn('pengeluaran', 'referensi_penggajian_id')) {
            DB::table('pengeluaran')
                ->whereNull('referensi_penggajian_id')
                ->update(['referensi_penggajian_id' => DB::raw('id_penggajian')]);
        }

        if (Schema::hasColumn('pengeluaran', 'jenis')) {
            DB::table('pengeluaran')
                ->whereNull('jenis')
                ->update(['jenis' => 'operasional']);
        }

        if (Schema::hasColumn('pengeluaran', 'referensi_penggajian_id')) {
            DB::statement("UPDATE pengeluaran p LEFT JOIN penggajian g ON g.id = p.referensi_penggajian_id SET p.referensi_penggajian_id = NULL WHERE p.referensi_penggajian_id IS NOT NULL AND g.id IS NULL");
        }

        if (Schema::hasColumn('pengeluaran', 'coa_debit_id')) {
            DB::statement("UPDATE pengeluaran p LEFT JOIN coa c ON c.id = p.coa_debit_id SET p.coa_debit_id = NULL WHERE p.coa_debit_id IS NOT NULL AND c.id IS NULL");
        }

        if (Schema::hasColumn('pengeluaran', 'coa_kredit_id')) {
            DB::statement("UPDATE pengeluaran p LEFT JOIN coa c ON c.id = p.coa_kredit_id SET p.coa_kredit_id = NULL WHERE p.coa_kredit_id IS NOT NULL AND c.id IS NULL");
        }

        if (Schema::hasColumn('pengeluaran', 'referensi_penggajian_id')) {
            $hasDup = DB::table('pengeluaran')
                ->select('referensi_penggajian_id', DB::raw('COUNT(*) as c'))
                ->whereNotNull('referensi_penggajian_id')
                ->groupBy('referensi_penggajian_id')
                ->having('c', '>', 1)
                ->exists();

            if (!$hasDup) {
                try {
                    Schema::table('pengeluaran', function (Blueprint $table) {
                        $table->unique('referensi_penggajian_id');
                    });
                } catch (Throwable $e) {
                }
            }
        }

        try {
            Schema::table('pengeluaran', function (Blueprint $table) {
                if (Schema::hasColumn('pengeluaran', 'referensi_penggajian_id')) {
                    $table->foreign('referensi_penggajian_id')->references('id')->on('penggajian')->nullOnDelete();
                }
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('pengeluaran', function (Blueprint $table) {
                if (Schema::hasColumn('pengeluaran', 'coa_debit_id')) {
                    $table->foreign('coa_debit_id')->references('id')->on('coa')->nullOnDelete();
                }
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('pengeluaran', function (Blueprint $table) {
                if (Schema::hasColumn('pengeluaran', 'coa_kredit_id')) {
                    $table->foreign('coa_kredit_id')->references('id')->on('coa')->nullOnDelete();
                }
            });
        } catch (Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            try { $table->dropForeign(['referensi_penggajian_id']); } catch (Throwable $e) {}
            try { $table->dropForeign(['coa_debit_id']); } catch (Throwable $e) {}
            try { $table->dropForeign(['coa_kredit_id']); } catch (Throwable $e) {}
            try { $table->dropUnique(['referensi_penggajian_id']); } catch (Throwable $e) {}

            if (Schema::hasColumn('pengeluaran', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (Schema::hasColumn('pengeluaran', 'nominal')) {
                $table->dropColumn('nominal');
            }
            if (Schema::hasColumn('pengeluaran', 'coa_kredit_id')) {
                $table->dropColumn('coa_kredit_id');
            }
            if (Schema::hasColumn('pengeluaran', 'coa_debit_id')) {
                $table->dropColumn('coa_debit_id');
            }
            if (Schema::hasColumn('pengeluaran', 'referensi_penggajian_id')) {
                $table->dropColumn('referensi_penggajian_id');
            }
            if (Schema::hasColumn('pengeluaran', 'jenis')) {
                $table->dropColumn('jenis');
            }
        });
    }
};
