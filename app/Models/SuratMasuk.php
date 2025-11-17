<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentTerm;
use App\Helpers\PreprocessingText;
use Illuminate\Support\Facades\Log;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    protected $fillable = ['nomor_surat','tanggal_surat','tanggal_terima','asal_surat','perihal','jenis_surat_id','file_path'];
    protected $casts = [
    'tanggal_surat'  => 'date:Y-m-d',
    'tanggal_terima' => 'date:Y-m-d',
    ];
    /* otomatis preprocessing & indeks saat create / update */
    protected static function booted(): void
    {
        static::created(fn($sm) => $sm->generateTerms());
        static::updated(fn($sm) => $sm->generateTerms());
    }

    public function generateTerms(): void
    {
        Log::info('generateTerms dipanggil', ['id' => $this->id, 'perihal' => $this->perihal]);

        DocumentTerm::where(['doc_id' => $this->id, 'doc_type' => 'masuk'])->delete();

        $tokens = PreprocessingText::preprocessText($this->perihal);
        Log::info('tokens', $tokens);

        if (!$tokens) {
            Log::info('kosong, return');
            return;
        }

        $freq = array_count_values($tokens);
        foreach ($freq as $term => $tf) {
            DocumentTerm::create([
                'doc_id'   => $this->id,
                'doc_type' => 'masuk',
                'term'     => $term,
                'tf'       => $tf
            ]);
            Log::info("insert", ['term' => $term, 'tf' => $tf]);
        }
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSuratMasuk::class, 'jenis_surat_id');
    }
}
