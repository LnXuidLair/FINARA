<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'jurnal_umum';

    protected $fillable = [
        'tgl',
        'no_referensi',
        'deskripsi'
    ];

    /**
     * Relasi ke Pengeluaran
     * satu jurnal bisa punya banyak pengeluaran
     */
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_jurnal');
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranSiswa::class, 'id_jurnal');
    }

}
