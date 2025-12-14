<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PembayaranSiswa extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_siswa';

    protected $fillable = [
        'id_siswa',
        'jenis_pembayaran',
        'tanggal_bayar',
        'jumlah',
        'status_pembayaran',
        'order_id',
        'snap_token',
        'id_jurnal',
    ];

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    /**
     * Relasi ke TagihanSiswa melalui pivot table
     */
    public function tagihanSiswa()
    {
        if (Schema::hasTable('tagihan_siswa_pembayaran')) {
            return $this->belongsToMany(
                TagihanSiswa::class,
                'tagihan_siswa_pembayaran',
                'pembayaran_siswa_id',
                'tagihan_siswa_id'
            );
        }

        if (Schema::hasColumn($this->getTable(), 'tagihan_siswa_id')) {
            return $this->belongsTo(TagihanSiswa::class, 'tagihan_siswa_id');
        }

        return $this->belongsTo(TagihanSiswa::class, 'id_siswa', 'siswa_id')
            ->whereRaw('1=0');
    }
}
