<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orangtua extends Model
{
    use HasFactory;

    // Nama tabel karena tidak mengikuti plural default Laravel (orangtuas)
    protected $table = 'orangtua';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'nik',
        'nama_ortu',
        'pekerjaan',
        'alamat',
        'no_telp',
        'gender',
    ];

    // Jika kamu ingin mapping gender lebih mudah
    public function getJenisKelaminAttribute()
    {
        return $this->gender == 1 ? 'Laki-laki' : 'Perempuan';
    }

    // Contoh relasi:
    // Asumsi: seorang orangtua punya banyak siswa (opsional jika ada nanti)
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_ortu', 'id');
    }
}
