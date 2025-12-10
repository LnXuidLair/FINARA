<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'penggajian';

    // Primary key (increments)
    protected $primaryKey = 'id';

    // Kolom yang boleh diisi melalui mass assignment
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

    // Casting otomatis
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi: Penggajian milik satu Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');
    }

    /**
     * Relasi: Penggajian terhubung ke satu Jurnal Umum
     */
    public function jurnal()
    {
        return $this->belongsTo(JurnalUmum::class, 'id_jurnal', 'id');
    }
}
