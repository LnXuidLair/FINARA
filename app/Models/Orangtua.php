<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orangtua extends Model
{
    protected $table = 'orangtua';

    protected $fillable = [
        'id_user',
        'nik',
        'nama_ortu',
        'pekerjaan',
        'alamat',
        'no_telp',
        'gender',
    ];

    // Relasi orangtua -> user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi orangtua -> siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orangtua');
    }
}
