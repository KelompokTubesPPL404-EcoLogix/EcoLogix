<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompensasiEmisiCarbon extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'kompensasi_emisi_carbon';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_kompensasi';

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
        'kode_kompensasi',
        'kode_emisi_carbon',
        'jumlah_kompensasi',
        'tanggal_kompensasi',
        'status_kompensasi',
        'kode_manager',
        'kode_perusahaan',
    ];

    /**
     * Relasi dengan Manager.
     */
    public function manager()
    {
        return $this->belongsTo(Manager::class, 'kode_manager', 'kode_manager');
    }

    /**
     * Relasi dengan Perusahaan.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'kode_perusahaan', 'kode_perusahaan');
    }

    /**
     * Relasi dengan EmisiCarbon.
     */
    public function emisiCarbon()
    {
        return $this->belongsTo(EmisiKarbon::class, 'kode_emisi_carbon', 'kode_emisi_carbon');
    }

    /**
     * Relasi dengan PembelianCarbonCredit.
     */
    public function pembelianCarbonCredit()
    {
        return $this->hasOne(PembelianCarbonCredit::class, 'kode_kompensasi', 'kode_kompensasi');
    }
}