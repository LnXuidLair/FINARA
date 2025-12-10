<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // Nama tabel (karena default Laravel adalah 'pegawais')
    protected $table = 'pegawai';

    // Primary key (karena memakai increments dan bukan bigIncrements)
    protected $primaryKey = 'id';

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'nip',
        'nama_pegawai',
        'jabatan',
        'email',
        'alamat',
        'is_verified',
    ];

    // Cast tipe data supaya boolean terbaca benar
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // Contoh relasi (opsional, jika ada tabel lain)
    // Misalnya: Pegawai memiliki banyak presensi
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'pegawai_id', 'id');
    }
}