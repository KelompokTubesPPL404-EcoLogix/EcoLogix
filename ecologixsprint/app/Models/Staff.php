<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    protected $table = 'staffs';
    
    protected $fillable = [
        'kode_staff',
        'nama_staff',
        'email',
        'password',
        'no_telepon'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getTable()
    {
        return $this->table;
    }

    public static function getColumns()
    {
        return [
            'kode_staff',
            'nama_staff',
            'email',
            'password',
            'no_telepon'
        ];
    }
}

