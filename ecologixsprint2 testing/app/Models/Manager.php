<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manager extends Authenticatable
{
    use HasFactory;
    protected $table = 'managers';
    
    protected $fillable = [
        'kode_manager',
        'nama_manager',
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
            'kode_manager',
            'nama_manager',
            'email',
            'password',
            'no_telepon'
        ];
    }
}
