<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nama_siswa',
        'nisn',
        'kelas',
        'alamat',
        'no_telp',
        'id_orangtua',
    ];

    // Relasi siswa -> orangtua (banyak siswa milik 1 orangtua)
    public function orangtua()
    {
        return $this->belongsTo(OrangTua::class, 'id_orangtua');
    }

    public function pembayaranSiswa()
    {
        return $this->hasManyThrough(
            PembayaranSiswa::class,
            TagihanSiswa::class,
            'siswa_id', // foreign key on tagihan_siswa table
            'tagihan_siswa_id', // foreign key on pembayaran_siswa table
            'id', // local key on siswa table
            'id' // local key on tagihan_siswa table
        );
    }

    public function tagihanSiswa()
    {
        return $this->hasMany(TagihanSiswa::class, 'siswa_id');
    }
}
