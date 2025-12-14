<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\Coa;
use App\Models\JurnalDetail;
use App\Models\JurnalUmum;
use App\Models\Pengeluaran;

class Penggajian extends Model
{
    protected $table = 'penggajian';

    protected $fillable = [
        'id_pegawai',
        'periode',
        'jumlah_hari',
        'gaji_perhari',
        'total_gaji',
        'status_penggajian',
        'tanggal',
        'id_jurnal',
        'pengeluaran_id',
        'jumlah_kehadiran',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($penggajian) {

            // === 1. Ambil otomatis gaji per hari dari gaji_jabatan ===
            if (empty($penggajian->gaji_perhari)) {
                $penggajian->gaji_perhari =
                    $penggajian->pegawai->gajiJabatan->gaji_perhari ?? 0;
            }

            // === 2. Hitung jumlah kehadiran otomatis dari tabel presensi ===
            if (empty($penggajian->jumlah_kehadiran)) {
                $penggajian->jumlah_kehadiran =
                    $penggajian->pegawai->hitungKehadiran($penggajian->periode);
            }

            // === 3. Hitung total gaji otomatis ===
            if (empty($penggajian->total_gaji)) {
                $penggajian->total_gaji =
                    $penggajian->gaji_perhari * $penggajian->jumlah_kehadiran;
            }
        });

        static::updated(function ($penggajian) {
            if (!$penggajian->wasChanged('status_penggajian')) {
                return;
            }

            if ($penggajian->status_penggajian !== 'dibayar') {
                return;
            }

            if (!empty($penggajian->pengeluaran_id) && !empty($penggajian->id_jurnal)) {
                return;
            }

            DB::transaction(function () use ($penggajian) {
                $bebanGaji = Coa::where('nama_akun', 'Beban Gaji')->firstOrFail();
                $kas = Coa::where('nama_akun', 'Kas')->firstOrFail();

                $tanggal = Carbon::today();
                $keterangan = 'Pembayaran Gaji Pegawai';

                $pengeluaran = Pengeluaran::where('referensi_penggajian_id', $penggajian->id)
                    ->lockForUpdate()
                    ->first();

                if (!$pengeluaran) {
                    $payload = [
                        'jenis' => 'gaji',
                        'referensi_penggajian_id' => $penggajian->id,
                        'coa_debit_id' => $bebanGaji->id,
                        'coa_kredit_id' => $kas->id,
                        'nominal' => (int) $penggajian->total_gaji,
                        'tanggal' => $tanggal,
                        'keterangan' => $keterangan,
                    ];

                    if (Schema::hasColumn('pengeluaran', 'kategori')) {
                        $payload['kategori'] = 'penggajian';
                    }
                    if (Schema::hasColumn('pengeluaran', 'deskripsi')) {
                        $payload['deskripsi'] = $keterangan;
                    }
                    if (Schema::hasColumn('pengeluaran', 'jumlah')) {
                        $payload['jumlah'] = (int) $penggajian->total_gaji;
                    }
                    if (Schema::hasColumn('pengeluaran', 'id_penggajian')) {
                        $payload['id_penggajian'] = $penggajian->id;
                    }
                    if (Schema::hasColumn('pengeluaran', 'bukti_pembayaran')) {
                        $payload['bukti_pembayaran'] = '-';
                    }

                    $pengeluaran = Pengeluaran::create($payload);
                }

                $jurnal = null;
                if (!empty($penggajian->id_jurnal)) {
                    $jurnal = JurnalUmum::lockForUpdate()->find($penggajian->id_jurnal);
                }

                if (!$jurnal) {
                    $jurnal = JurnalUmum::create([
                        'tgl' => $tanggal,
                        'no_referensi' => 'GJ-' . $penggajian->id,
                        'deskripsi' => $keterangan,
                    ]);
                }

                $detailsExist = JurnalDetail::where('jurnal_id', $jurnal->id)->exists();
                if (!$detailsExist) {
                    JurnalDetail::create([
                        'jurnal_id' => $jurnal->id,
                        'coa_id' => $bebanGaji->id,
                        'deskripsi' => $keterangan,
                        'debit' => (float) $penggajian->total_gaji,
                        'credit' => 0,
                    ]);

                    JurnalDetail::create([
                        'jurnal_id' => $jurnal->id,
                        'coa_id' => $kas->id,
                        'deskripsi' => $keterangan,
                        'debit' => 0,
                        'credit' => (float) $penggajian->total_gaji,
                    ]);
                }

                $penggajian->forceFill([
                    'id_jurnal' => $jurnal->id,
                    'pengeluaran_id' => $pengeluaran->id,
                ])->saveQuietly();

                $pengeluaran->forceFill([
                    'id_jurnal' => $jurnal->id,
                    'status_verifikasi' => 'approved',
                ])->saveQuietly();
            });
        });
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function jurnal()
    {
        return $this->belongsTo(JurnalUmum::class, 'id_jurnal');
    }

    public function pengeluaran()
    {
        return $this->hasOne(Pengeluaran::class, 'id', 'pengeluaran_id');
    }
}
