<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $fillable = ['nomor_surat','tanggal_surat','tujuan_surat','perihal','penanggung_jawab','file_path'];
}
