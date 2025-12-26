<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTerm extends Model
{
    use HasFactory;

    protected $table = 'surat_terms';

    protected $fillable = [
        'surat_type',
        'surat_id',
        'term',
        'tf',
        'tfidf',
    ];

    // helper ambil surat-nya (manual karena enum)
    public function surat()
    {
        return $this->surat_type === 'masuk'
            ? SuratMasuk::find($this->surat_id)
            : SuratKeluar::find($this->surat_id);
    }
}