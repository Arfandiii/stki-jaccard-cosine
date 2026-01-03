<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan_surat',
        'perihal',
        'penanggung_jawab',
        'file_path',
    ];
    
    protected $casts = [
        'tanggal_surat'  => 'date:Y-m-d',
    ];

    public function terms()
    {
        return $this->hasMany(SuratTerm::class, 'surat_id')
                    ->where('surat_type', 'keluar');
    }
    
    protected static function booted()
    {
        static::deleting(function ($surat) {
            DB::table('surat_terms')
                ->where('surat_type', 'keluar')
                ->where('surat_id', $surat->id)
                ->delete();
        });
    }
}