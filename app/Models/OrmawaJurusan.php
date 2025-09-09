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

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function verifyPassword($password)
    {
        return Hash::check($password, $this->password);
    }
}
