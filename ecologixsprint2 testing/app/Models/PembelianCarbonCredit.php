<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianCarbonCredit extends Model
{
    use HasFactory;
    
    protected $table = 'pembelian_carbon_credits';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_pembelian_carbon_credit',
        'kode_kompensasi',
        'kode_penyedia',
        'jumlah_kompensasi',
        'harga_per_ton',
        'total_harga',
        'deskripsi',
        'bukti_pembelian',
        'status'
    ];

    public function penyediaCarbonCredit()
    {
        return $this->belongsTo(PenyediaCarbonCredit::class, 'kode_penyedia', 'kode_penyedia');
    }

    public function kompensasiEmisi()
    {
        return $this->belongsTo(KompensasiEmisi::class, 'kode_kompensasi', 'kode_kompensasi');
    }
}
