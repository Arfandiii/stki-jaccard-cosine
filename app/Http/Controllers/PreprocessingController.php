<?php

namespace App\Http\Controllers;

use App\Helpers\PreprocessingText;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\SuratTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreprocessingController extends Controller
{
    /**
     * Proses preprocessing seluruh surat
     */
    public function process()
    {
        DB::transaction(function () {

            // 1. Kosongkan tabel term (opsional, tapi aman)
            SuratTerm::truncate();

            // 2. Proses surat masuk
            $this->processSurat(
                SuratMasuk::all(),
                'masuk'
            );

            // 3. Proses surat keluar
            $this->processSurat(
                SuratKeluar::all(),
                'keluar'
            );
        });

        return back()->with('success', 'Preprocessing surat berhasil diproses');
    }

    /**
     * Preprocessing + simpan TF
     */
    private function processSurat($suratCollection, string $type)
    {
        foreach ($suratCollection as $surat) {

            // Ambil teks perihal
            $text = $surat->perihal ?? '';
            if (trim($text) === '') continue;

            // Preprocessing
            $tokens = PreprocessingText::preprocessText($text);

            // Hitung TF
            $tfCounts = array_count_values($tokens);

            // Simpan ke tabel surat_terms
            foreach ($tfCounts as $term => $count) {
                SuratTerm::updateOrCreate(
                    [
                        'surat_type' => $type,
                        'surat_id'   => $surat->id,
                        'term'       => $term
                    ],
                    [
                        'tf' => $count
                    ]
                );
            }
        }
    }
}
