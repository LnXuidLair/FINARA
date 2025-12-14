<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    use HasFactory;

    protected $table = 'jurnal_detail';

    protected $fillable = [
        'jurnal_id',
        'coa_id',
        'deskripsi',
        'debit',
        'credit',
    ];

    // Relasi ke Jurnal Umum
    public function jurnal()
    {
        return $this->belongsTo(JurnalUmum::class, 'jurnal_id');
    }

    // Relasi ke COA
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}
