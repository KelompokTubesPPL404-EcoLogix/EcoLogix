<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianCarbonCredit extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'pembelian_carbon_credit';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_pembelian_carbon_credit';

    /**
     * Menunjukkan bahwa primary key bukan auto-increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe data primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Atribut yang dapat diisi (mass assignable).
     *
     * @var array<string>
     */
    protected $fillable = [
        'kode_pembelian_carbon_credit',
        'kode_penyedia',
        'kode_kompensasi',
        'kode_admin',
        'jumlah_kompensasi',
        'harga_per_ton',
        'total_harga',
        'tanggal_pembelian',
        'bukti_pembayaran',
        'deskripsi',
    ];

    /**
     * Relasi dengan PenyediaCarbonCredit.
     */
    public function penyedia()
    {
        return $this->belongsTo(PenyediaCarbonCredit::class, 'kode_penyedia', 'kode_penyedia');
    }

    /**
     * Relasi dengan KompensasiEmisiCarbon.
     */
    public function kompensasiEmisiCarbon()
    {
        return $this->belongsTo(KompensasiEmisiCarbon::class, 'kode_kompensasi', 'kode_kompensasi');
    }

    /**
     * Relasi dengan Admin.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'kode_admin', 'kode_admin');
    }
}