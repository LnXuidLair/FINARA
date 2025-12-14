<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'pembayaran_siswa_id',
        'order_id',
        'gross_amount',
        'snap_token',
        'transaction_status',
        'payment_type',
        'status_code',
        'fraud_status',
        'payload',
        'transaction_time',
    ];

    protected $casts = [
        'payload' => 'array',
        'transaction_time' => 'datetime',
    ];

    public function pembayaranSiswa()
    {
        return $this->belongsTo(PembayaranSiswa::class, 'pembayaran_siswa_id');
    }
}
