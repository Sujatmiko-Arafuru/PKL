<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class OrmawaJurusan extends Model
{
    protected $table = 'ormawa_jurusan';
    
    protected $fillable = [
        'nama',
        'tipe',
        'password',
        'email',
        'no_telp',
        'alamat',
        'is_active'
    ];

    protected $hidden = [
        'password'
    ];

    // Method to get password for admin views
    public function getPasswordForAdmin()
    {
        // For ormawa and jurusan, return plain text password
        // For other types, return hashed password
        return $this->attributes['password'];
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function setPasswordAttribute($value)
    {
        // Always store password as plain text - no hashing
        $this->attributes['password'] = $value;
    }

    public function verifyPassword($password)
    {
        // Always compare plain text passwords
        return $password === $this->password;
    }
}
