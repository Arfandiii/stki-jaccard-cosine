<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisSuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat_masuk';

    protected $fillable = [
        'nama_jenis',
    ];

    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class, 'jenis_surat_id');
    }
}
