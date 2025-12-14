<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class TagihanSiswa extends Model
{
    use HasFactory;

    protected $table = 'tagihan_siswa';

    protected $fillable = [
        'siswa_id',
        'orangtua_id',
        'jenis_tagihan',
        'nominal',
        'periode',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function pembayaranSiswa()
    {
        if (Schema::hasTable('tagihan_siswa_pembayaran')) {
            return $this->belongsToMany(
                PembayaranSiswa::class,
                'tagihan_siswa_pembayaran',
                'tagihan_siswa_id',
                'pembayaran_siswa_id'
            );
        }

        if (Schema::hasColumn('pembayaran_siswa', 'tagihan_siswa_id')) {
            return $this->hasMany(PembayaranSiswa::class, 'tagihan_siswa_id', 'id');
        }

        // Fallback: cari pembayaran berdasarkan siswa_id dan jumlah yang cocok
        return $this->hasMany(PembayaranSiswa::class, 'id_siswa', 'siswa_id')
            ->where('status_pembayaran', 'lunas');
    }

    public function getTotalPembayaranAttribute()
    {
        if ($this->relationLoaded('pembayaranSiswa')) {
            return (int) $this->pembayaranSiswa
                ->where('status_pembayaran', 'lunas')
                ->sum('jumlah');
        }
        
        return (int) $this->pembayaranSiswa()
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah');
    }

    /**
     * Get the payment status based on payment history
     * Status LUNAS hanya jika tagihan ini spesifik terhubung dengan pembayaran lunas
     */
    public function getStatusAttribute()
    {
        // Check if this specific tagihan is connected to a lunas payment
        $hasConnectedPayment = $this->pembayaranSiswa()
            ->where('status_pembayaran', 'lunas')
            ->exists();
            
        return $hasConnectedPayment ? 'lunas' : 'belum lunas';
    }

    public function getSisaAttribute()
    {
        $totalTagihan = (int) ($this->nominal ?? $this->jumlah ?? 0);
        $sisa = $totalTagihan - (int) $this->total_pembayaran;

        return $sisa > 0 ? $sisa : 0;
    }
}
