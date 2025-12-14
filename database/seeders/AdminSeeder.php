<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create parent user
        $parentUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'orangtua',
        ]);

        // Create orang tua
        $orangTua = OrangTua::create([
            'nik' => '1234567890123456',
            'nama_ortu' => 'Budi Santoso',
            'pekerjaan' => 'Pegawai Swasta',
            'alamat' => 'Jakarta',
            'no_telp' => '08123456789',
            'gender' => 1, // 1 = Laki-laki
        ]);

        // Create students
        $siswa1 = Siswa::create([
            'nama_siswa' => 'Ahmad Rizki',
            'nisn' => '2024001',
            'kelas' => 'XII-1',
            'id_orangtua' => $orangTua->id,
        ]);

        $siswa2 = Siswa::create([
            'nama_siswa' => 'Siti Nurhaliza',
            'nisn' => '2024002',
            'kelas' => 'XI-2',
            'id_orangtua' => $orangTua->id,
        ]);

        // Create tagihan for siswa1
        TagihanSiswa::create([
            'siswa_id' => $siswa1->id,
            'orangtua_id' => $orangTua->id,
            'jenis_tagihan' => 'SPP Bulanan',
            'nominal' => 500000,
            'periode' => '2025-01',
        ]);

        TagihanSiswa::create([
            'siswa_id' => $siswa1->id,
            'orangtua_id' => $orangTua->id,
            'jenis_tagihan' => 'Uang Gedung',
            'nominal' => 2000000,
            'periode' => '2025',
        ]);

        // Create tagihan for siswa2
        TagihanSiswa::create([
            'siswa_id' => $siswa2->id,
            'orangtua_id' => $orangTua->id,
            'jenis_tagihan' => 'SPP Bulanan',
            'nominal' => 500000,
            'periode' => '2025-01',
        ]);

        TagihanSiswa::create([
            'siswa_id' => $siswa2->id,
            'orangtua_id' => $orangTua->id,
            'jenis_tagihan' => 'Biaya Laboratorium',
            'nominal' => 750000,
            'periode' => '2025-01',
        ]);

        $this->command->info('Admin and test data created successfully!');
        $this->command->info('Admin login: admin@example.com / password');
        $this->command->info('Parent login: budi@example.com / password');
    }
}
