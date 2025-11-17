<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisSuratMasuk;

class JenisSuratMasukSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            'Surat Keputusan',
            'Surat Laporan',
            'Surat Pemberitahuan',
            'Surat Pengaduan',
            'Surat Pengajuan',
            'Surat Permintaan Data',
            'Surat Permohonan',
            'Surat Rekomendasi',
            'Surat Tugas',
            'Surat Undangan',
        ];

        foreach ($jenis as $nama) {
            JenisSuratMasuk::create([
                'nama_jenis' => $nama
            ]);
        }
    }
}