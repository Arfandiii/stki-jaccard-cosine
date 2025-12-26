<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\IndexableSurat;

class SuratKeluar extends Model
{
    use HasFactory, IndexableSurat;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan_surat',
        'perihal',
        'penanggung_jawab',
        'file_path',
    ];
}