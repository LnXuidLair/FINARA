<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    // protected $primaryKey = 'kode_bahanbaku';
    protected $guarded = '';
    // query nilai max dari kode distributor untuk generate otomatis kode distributor
    public static function generateKodeproduk()
    {
        // query kode distributor
        $sql = "SELECT IFNULL(MAX(kode_produk), 'PR-000') as kode_produk 
                FROM produk";
        $kodeproduk = DB::select($sql);

        // cacah hasilnya
        foreach ($kodeproduk as $idcst) {
            $kd = $idcst->kode_produk;
        }
       // Mengambil substring tiga digit akhir dari string BB-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal + 1; //menambahkan 1, hasilnya adalah integer, contoh 1
    
        // menyambung dengan string BB-001
        $noakhir = 'PR-' . str_pad($noakhir, 3, "0", STR_PAD_LEFT); 

        return $noakhir;

    }

}
