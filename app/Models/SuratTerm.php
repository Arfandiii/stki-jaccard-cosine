<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTerm extends Model
{
    use HasFactory;

    protected $table = 'surat_terms';

    protected $fillable = [
        'surat_type', // 'masuk' | 'keluar'
        'surat_id',
        'term',
        'tf',
        'tfidf',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_id')
                    ->where('surat_type', 'masuk');
    }

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_id')
                    ->where('surat_type', 'keluar');
    }

    public function surat()
    {
        if ($this->surat_type === 'masuk') {
            return SuratMasuk::find($this->surat_id);
        }

        return SuratKeluar::find($this->surat_id);
    }
}