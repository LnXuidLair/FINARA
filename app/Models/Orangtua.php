<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $table = 'orangtua';

    protected $fillable = [
        'nik',
        'nama_ortu',
        'email',
        'pekerjaan',
        'alamat',
        'no_telp',
        'gender',
    ];

    // Relasi orangtua -> user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi orangtua -> siswa (1 orangtua punya banyak siswa)
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orangtua');
    }

    // Relasi untuk mendapatkan tagihan melalui siswa
    public function tagihanSiswa()
    {
        return $this->hasManyThrough(
            TagihanSiswa::class,
            Siswa::class,
            'id_orangtua', // foreign key on siswa table
            'siswa_id', // foreign key on tagihan_siswa table
            'id', // local key on orangtua table
            'id' // local key on siswa table
        );
    }
}
