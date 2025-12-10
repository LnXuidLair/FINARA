<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiJabatan extends Model
{
    use HasFactory;

    protected $table = 'gaji_jabatan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jabatan',
        'gaji_pokok',
        'tunjangan',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan' => 'decimal:2',
    ];

    /**
     * Relasi ke model Pegawai
     */
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'gaji_jabatan_id');
    }

    /**
     * Hitung total gaji (gaji pokok + tunjangan)
     */
    public function getTotalGajiAttribute()
    {
        return $this->gaji_pokok + $this->tunjangan;
    }
}
