<?php

namespace App\Helpers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\SuratTerm;
use Illuminate\Support\Facades\DB;

class TfidfService
{
    public static function calculate(string $suratType): void
    {
        // 1️⃣ Ambil semua term berdasarkan tipe surat
        $terms = DB::table('surat_terms')
            ->where('surat_type', $suratType)
            ->select('surat_id', 'term', 'tf')
            ->get();

        if ($terms->isEmpty()) return;

        // 2️⃣ Jumlah dokumen
        $totalDocs = $terms->pluck('surat_id')->unique()->count();

        // 3️⃣ Hitung DF
        $df = $terms
            ->groupBy('term')
            ->map(fn($rows) => $rows->pluck('surat_id')->unique()->count());

        // 4️⃣ Hitung IDF
        $idf = [];
        foreach ($df as $term => $docCount) {
            $idf[$term] = $docCount > 0
                ? round(log10($totalDocs / $docCount), 4)
                : 0;
        }

        // 5️⃣ Update TF-IDF
        foreach ($terms as $row) {
            $tfWeight = 1 + log10($row->tf);
            $tfidf = round($tfWeight * ($idf[$row->term] ?? 0), 4);

            DB::table('surat_terms')
                ->where('surat_type', $suratType)
                ->where('surat_id', $row->surat_id)
                ->where('term', $row->term)
                ->update(['tfidf' => $tfidf]);
        }
    }
}
