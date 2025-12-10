<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Nama tabel (karena default Laravel = 'siswas')
    protected $table = 'siswa';

    // Primary key default sudah benar, jadi tidak perlu diubah
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi mass assignment
    protected $fillable = [
        'nisn',
        'nama_siswa',
        'kelas',
        'alamat',
        'no_telp',
        'id_orangtua',
    ];

    // Relasi: Siswa milik 1 Orangtua
    public function orangtua()
    {
        return $this->belongsTo(Orangtua::class, 'id_orangtua', 'id');
    }

    // Relasi tambahan (opsional) jika siswa memiliki pembayaran
    public function pembayaran()
    {
        return $this->hasMany(PembayaranSiswa::class, 'id_siswa', 'id');
    }
}
