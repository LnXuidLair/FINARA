<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran'; // nama tabel

    protected $fillable = [
        'tanggal',
        'jenis',
        'referensi_penggajian_id',
        'coa_debit_id',
        'coa_kredit_id',
        'nominal',
        'keterangan',
        'kategori',
        'deskripsi',
        'jumlah',
        'id_penggajian',
        'bukti_pembayaran',
        'status_verifikasi',
        'catatan_verifikasi',
        'id_jurnal'
    ];

    /**
     * Relasi ke Penggajian
     * pengeluaran belongsTo penggajian
     */
    public function debit()
    {
        return $this->belongsTo(Coa::class, 'coa_debit_id');
    }

    public function kredit()
    {
        return $this->belongsTo(Coa::class, 'coa_kredit_id');
    }

    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class, 'referensi_penggajian_id');
    }
}
