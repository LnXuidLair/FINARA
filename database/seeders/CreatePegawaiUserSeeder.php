<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreatePegawaiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat user pegawai baru
        User::create([
            'name' => 'Pegawai Test',
            'email' => 'pegawai@test.com',
            'username' => 'pegawai',
            'password' => Hash::make('pegawai123'), // password: pegawai123
            'role' => 'pegawai',
        ]);

        $this->command->info('User pegawai berhasil dibuat!');
        $this->command->info('Username: pegawai');
        $this->command->info('Password: pegawai123');
    }
}
