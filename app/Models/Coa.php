<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'coa';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'kode_akun',
        'nama_akun',
    ];

}
