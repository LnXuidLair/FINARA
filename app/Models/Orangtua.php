<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orangtua extends Model
{
    protected $table = 'orangtua';

    protected $fillable = [
        'nik',
        'nama_orangtua',
        'pekerjaan',
        'alamat',
        'no_telp',
        'gender',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orangtua');
    }
}
