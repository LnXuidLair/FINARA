<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'password_changed_at',
        'role', // admin, orangtua, kepala_sekolah
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
    ];

    // ============================
    // RELASI USER -> ORANGTUA
    // ============================
    public function orangtua()
    {
        return $this->hasOne(Orangtua::class, 'id_user');
    }


    // RELASI USER -> PEGAWAI
    
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user');
    }

    // Optional: bantu cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOrangtua()
    {
        return $this->role === 'orangtua';
    }

    public function isKepalaSekolah()
    {
        return $this->role === 'kepala_sekolah';
    }
}
