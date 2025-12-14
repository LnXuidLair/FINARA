<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $table = 'coa';

    protected $fillable = [
        'header_akun',
        'kode_akun',
        'nama_akun',
    ];

    // Relasi: 1 COA punya banyak jurnal detail
    public function jurnalDetail()
    {
        return $this->hasMany(JurnalDetail::class, 'coa_id');
    }
}
