<?php

namespace Database\Seeders;

use App\Models\PembayaranSiswa;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSiswaSeeder extends Seeder
{
    public function run()
    {
        // Get all students
        $siswa = Siswa::all();
        
        if ($siswa->isEmpty()) {
            $this->call(SiswaSeeder::class);
            $siswa = Siswa::all();
        }

        $jenisPembayaran = ['SPP', 'Daftar Ulang', 'Uang Gedung', 'Uang Seragam', 'Uang Buku'];
        $statusPembayaran = ['pending', 'lunas', 'gagal'];

        foreach ($siswa as $s) {
            // Create 3-5 random payments for each student
            $jumlahPembayaran = rand(3, 5);
            
            for ($i = 0; $i < $jumlahPembayaran; $i++) {
                $jenis = $jenisPembayaran[array_rand($jenisPembayaran)];
                $status = $statusPembayaran[array_rand($statusPembayaran)];
                $bulan = rand(1, 12);
                $tahun = date('Y') - rand(0, 1);
                
                PembayaranSiswa::create([
                    'id_siswa' => $s->id,
                    'jenis_pembayaran' => $jenis,
                    'tanggal_bayar' => date('Y-m-d', strtotime("$tahun-$bulan-" . rand(1, 28))),
                    'jumlah' => $this->getJumlahPembayaran($jenis),
                    'status_pembayaran' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function getJumlahPembayaran($jenis)
    {
        return match($jenis) {
            'SPP' => 500000,
            'Daftar Ulang' => 1500000,
            'Uang Gedung' => 2000000,
            'Uang Seragam' => 1000000,
            'Uang Buku' => 750000,
            default => 500000,
        };
    }
}
