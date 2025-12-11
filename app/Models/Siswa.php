<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nisn',
        'nama',
        'kelas',
        'no_telepon',
        'id_orangtua',
    ];

    public function orangtua()
    {
        return $this->belongsTo(Orangtua::class, 'id_orangtua');
    }

    public function pembayaranSiswa()
    {
        return $this->hasMany(PembayaranSiswa::class, 'id_siswa');
    }
}
