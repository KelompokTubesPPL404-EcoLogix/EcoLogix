<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    use HasFactory;
    protected $table = 'super_admins';
    
    protected $fillable = [
        'kode_super_admin',
        'nama_super_admin', 
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
            'kode_super_admin',
            'nama_super_admin', 
            'email',
            'password',
            'no_telepon'
        ];
    }    
}