<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nip',
        'nama_pegawai',
        'jabatan',
        'email',
        'alamat',
        'no_telp',
        'is_verified',
    ];

    // Relasi ke presensi
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'id_pegawai');
    }

    // Relasi ke penggajian
    public function penggajians()
    {
        return $this->hasMany(Penggajian::class, 'id_pegawai');
    }
}
