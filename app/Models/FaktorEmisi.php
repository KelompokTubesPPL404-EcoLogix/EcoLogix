<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaktorEmisi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'faktor_emisi';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_faktor';

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
        'kode_faktor',
        'kategori_emisi_karbon',
        'sub_kategori',
        'nilai_faktor',
        'satuan',
    ];

    /**
     * Relasi dengan EmisiCarbon.
     */
    public function emisiCarbon()
    {
        return $this->hasMany(EmisiCarbon::class, 'kode_faktor_emisi', 'kode_faktor');
    }
}