<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'id_user',
        'nip',
        'nama_pegawai',
        'jabatan',
        'email',
        'alamat',
        'no_telp',
        'is_verified',
        'id_gaji_jabatan',
    ];

        // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke gaji_jabatan
    public function gajiJabatan()
    {
        return $this->belongsTo(GajiJabatan::class, 'id_gaji_jabatan');
    }

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
    public function hitungKehadiran($periode)
    {
        return $this->presensis()
            ->whereMonth('tanggal', date('m', strtotime($periode)))
            ->whereYear('tanggal', date('Y', strtotime($periode)))
            ->where('status', 'hadir')
            ->count();
    }

}