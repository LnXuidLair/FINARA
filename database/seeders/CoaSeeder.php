<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coa;

class CoaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'header_akun' => 'Aset',
                'kode_akun' => '10001',
                'nama_akun' => 'Kas',
            ],
            [
                'header_akun' => 'Beban',
                'kode_akun' => '50001',
                'nama_akun' => 'Beban Gaji',
            ],
        ];

        foreach ($items as $item) {
            Coa::firstOrCreate(
                ['nama_akun' => $item['nama_akun']],
                $item
            );
        }
    }
}
