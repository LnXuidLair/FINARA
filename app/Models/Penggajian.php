<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'jumlah_kehadiran',
    ];

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
        return $this->hasMany(Pengeluaran::class, 'id_penggajian');
    }
}
