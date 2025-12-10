<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranSiswa extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_siswa';

    protected $fillable = [
        'id_siswa',
        'jenis_pembayaran',
        'tanggal_bayar',
        'jumlah',
        'status_pembayaran',
        'id_jurnal',
    ];

    /**
     * Relasi ke Siswa
     * pembayaran_siswa.id_siswa → siswa.id
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    /**
     * Relasi ke Jurnal Umum
     * pembayaran_siswa.id_jurnal → jurnal_umum.id
     */
    public function jurnal()
    {
        return $this->belongsTo(JurnalUmum::class, 'id_jurnal');
    }
}
