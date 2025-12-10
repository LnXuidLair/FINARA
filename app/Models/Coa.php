<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coa';

    protected $fillable = [
        'header_akun',
        'kode_akun',
        'nama_akun',
    ];

    /**
     * Relasi ke JurnalDetail
     */
    public function jurnalDetail()
    {
        return $this->hasMany(JurnalDetail::class, 'coa_id');
    }
}
