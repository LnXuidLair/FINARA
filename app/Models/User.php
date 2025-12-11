<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Tabel sudah default (users), jadi tidak perlu protected $table

    /**
     * Kolom yang boleh di-mass assign
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang harus disembunyikan ketika data di-return (misal ke JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting otomatis
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mutator untuk otomatis hash password saat disimpan
     */
    public function setPasswordAttribute($value)
    {
        // Supaya tidak double-hash jika sudah terenkripsi
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Contoh relasi (opsional)
     * Jika user role = pegawai → mungkin terhubung ke tabel pegawai
     */
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'email', 'email');
    }

    /**
     * Jika user role = orangtua → mungkin terhubung ke tabel orangtua
     */
    public function orangtua()
    {
        return $this->hasOne(Orangtua::class, 'nik', 'email'); 
        // Sesuaikan jika kolom penghubungnya beda.
    }
}
