<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    // Nama tabel (karena tidak jamak / plural)
    protected $table = 'presensi';

    // Field yang bisa diisi (fillable)
    protected $fillable = [
        'id_pegawai',
        'tanggal',
        'status',
    ];

    /**
     * Relasi ke tabel pegawai
     * Setiap presensi dimiliki oleh satu pegawai.
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
