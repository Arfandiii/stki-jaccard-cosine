<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\IndexableSurat;

class SuratMasuk extends Model
{
    use HasFactory, IndexableSurat;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'asal_surat',
        'perihal',
        'jenis_surat_id',
        'file_path',
    ];

    protected $casts = [
        'tanggal_surat'  => 'date:Y-m-d',
        'tanggal_terima' => 'date:Y-m-d',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSuratMasuk::class, 'jenis_surat_id');
    }

    // app/Models/SuratMasuk.php
    public function terms()
    {
        return $this->hasMany(SuratTerm::class, 'surat_id')
                    ->where('surat_type', 'masuk');
    }
}