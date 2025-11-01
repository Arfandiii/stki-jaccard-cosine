<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentTerm;
use App\Helpers\PreprocessingText;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    protected $fillable = ['nomor_surat','tanggal_surat','tanggal_terima','asal_surat','perihal','jenis_surat','file_path'];

    /* otomatis preprocessing & indeks saat create / update */
    protected static function booted(): void
    {
        static::created(fn($sm) => $sm->generateTerms());
        static::updated(fn($sm) => $sm->generateTerms());
    }

    public function generateTerms(): void
    {
        // 1. bersihkan lama
        DocumentTerm::where([
            'doc_id'   => $this->id,
            'doc_type' => 'masuk'
        ])->delete();

        // 2. preprocessing
        $tokens = PreprocessingText::preprocessText($this->perihal);
        if (!$tokens) return;

        // 3. frekuensi
        $freq = array_count_values($tokens);

        // 4. simpan baru
        foreach ($freq as $term => $tf) {
            DocumentTerm::create([
                'doc_id'   => $this->id,
                'doc_type' => 'masuk',
                'term'     => $term,
                'tf'       => $tf
            ]);
        }
    }
}
