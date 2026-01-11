<?php

namespace App\Helpers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\SuratTerm;
use Illuminate\Support\Facades\DB;

class TfidfService
{
    /**
     * Hitung TF-IDF dan TF-IDF norm untuk seluruh surat tertentu
     * Langkah:
     *  1. TF sudah terisi
     *  2. Hitung DF & IDF
     *  3. Update tfidf
     *  4. Hitung panjang vektor tiap dokumen
     *  5. Update tfidf_norm
     */
    public static function calculate(string $suratType): void
    {
        /* ---------- 2. jumlah dokumen ---------- */
        $totalDocs = DB::table('surat_terms')
                    ->where('surat_type', $suratType)
                    ->distinct('surat_id')
                    ->count('surat_id');

        if ($totalDocs === 0) return;

        /* ---------- 3. DF tiap term ---------- */
        $df = DB::table('surat_terms')
                ->where('surat_type', $suratType)
                ->selectRaw('term, COUNT(DISTINCT surat_id) as df')
                ->groupBy('term')
                ->pluck('df', 'term'); // ['kata'=>df]

        /* ---------- 4. IDF ---------- */
        $idf = $df->map(fn($v) => $v > 0 ? round(log10($totalDocs / $v), 4) : 0);

        /* ---------- 5. update tfidf ---------- */
        $rows = DB::table('surat_terms')
                ->where('surat_type', $suratType)
                ->select('id', 'surat_id', 'term', 'tf')
                ->get();

        foreach ($rows as $r) {
            $tfWeight = 1 + log10($r->tf);
            $tfidf    = round($tfWeight * $idf[$r->term], 4);
            DB::table('surat_terms')
            ->where('id', $r->id)
            ->update(['tfidf' => $tfidf]);
        }

        /* 6. baru hitung panjang + update norm */
        $length = DB::table('surat_terms')
                    ->where('surat_type', $suratType)
                    ->selectRaw('surat_id, SQRT(SUM(POWER(tfidf,2))) as len')
                    ->groupBy('surat_id')
                    ->pluck('len', 'surat_id');

        foreach ($rows as $r) {
            $vec  = DB::table('surat_terms')->where('id', $r->id)->value('tfidf');
            $len  = $length[$r->surat_id] ?? 0;
            $norm = $len > 0 ? round($vec / $len, 4) : 0;

            DB::table('surat_terms')
            ->where('id', $r->id)
            ->update(['tfidf_norm' => $norm]);
        }
    }
}
