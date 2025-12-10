<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran'; // nama tabel

    protected $fillable = [
        'id_penggajian',
        'kategori',
        'deskripsi',
        'jumlah',
        'tanggal',
        'bukti_pembayaran',
        'id_jurnal'
    ];

    /**
     * Relasi ke Penggajian
     * pengeluaran belongsTo penggajian
     */
    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class, 'id_penggajian');
    }

    /**
     * Relasi ke Jurnal Umum
     * pengeluaran belongsTo jurnal umum
     */
    public function jurnal()
    {
        return $this->belongsTo(JurnalUmum::class, 'id_jurnal');
    }
}
