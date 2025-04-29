<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'super_admin';
    
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