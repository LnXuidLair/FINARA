<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SetoranModal extends Model
{
    use HasFactory;

    protected $table = 'setoranmodal';
    // protected $primaryKey = 'kode_modal';
    protected $guarded = '';
    // query nilai max dari kode distributor untuk generate otomatis kode distributor
    public static function generateKodemodal()
    {
        // query kode distributor
        $sql = "SELECT IFNULL(MAX(kode_modal), 'SM-000') as kode_modal
                FROM setoranmodal";
        $kode_modal = DB::select($sql);

        // cacah hasilnya
        foreach ($kode_modal as $idcst) {
            $kd = $idcst->kode_modal;
        }
       // Mengambil substring tiga digit akhir dari string BB-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal + 1; //menambahkan 1, hasilnya adalah integer, contoh 1
    
        // menyambung dengan string BB-001
        $noakhir = 'SM-' . str_pad($noakhir, 3, "0", STR_PAD_LEFT); 

        return $noakhir;

    }

}
