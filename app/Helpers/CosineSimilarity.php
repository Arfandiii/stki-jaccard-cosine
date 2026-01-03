<?php

namespace App\Helpers;

use App\Helpers\PreprocessingText;
use Illuminate\Support\Facades\DB;

class CosineSimilarity
{
    /**
     * Hitung cosine similarity antara query dan surat
     * @param string $query
     * @param string $suratType  'masuk' | 'keluar'
     * @return array
     */
    public static function calculate(string $query, string $suratType): array
    {
        /* =============================
         * 1️⃣ Preprocessing query
         * ============================= */
        $queryTokens = PreprocessingText::preprocessText($query);

        if (empty($queryTokens)) return [];

        $queryTf = array_count_values($queryTokens);

        /* =============================
         * 2️⃣ Ambil IDF dari database
         * ============================= */
        $idfMap = DB::table('surat_terms')
            ->where('surat_type', $suratType)
            ->select('term', DB::raw('MAX(tfidf / tf) as idf'))
            ->groupBy('term')
            ->pluck('idf', 'term')
            ->toArray();

        /* =============================
         * 3️⃣ Hitung TF-IDF query
         * ============================= */
        $queryVector = [];
        foreach ($queryTf as $term => $tf) {
            if (!isset($idfMap[$term])) continue;

            $tfWeight = 1 + log10($tf);
            $queryVector[$term] = $tfWeight * $idfMap[$term];
        }

        $queryNorm = sqrt(array_sum(array_map(fn($v) => $v * $v, $queryVector)));
        if ($queryNorm == 0) return [];

        /* =============================
         * 4️⃣ Ambil TF-IDF dokumen
         * ============================= */
        $documents = DB::table('surat_terms')
            ->where('surat_type', $suratType)
            ->whereIn('term', array_keys($queryVector))
            ->select('surat_id', 'term', 'tfidf')
            ->get()
            ->groupBy('surat_id');

        /* =============================
         * 5️⃣ Cosine Similarity
         * ============================= */
        $results = [];

        foreach ($documents as $suratId => $rows) {

            $dotProduct = 0;
            $docNormSq  = 0;

            foreach ($rows as $row) {
                $qVal = $queryVector[$row->term] ?? 0;
                $dotProduct += $qVal * $row->tfidf;
                $docNormSq  += $row->tfidf ** 2;
            }

            $docNorm = sqrt($docNormSq);
            $cosine  = ($docNorm > 0)
                ? $dotProduct / ($queryNorm * $docNorm)
                : 0;

            $results[] = [
                'surat_id'  => $suratId,
                'similarity'=> round($cosine, 6)
            ];
        }

        /* =============================
         * 6️⃣ Urutkan hasil
         * ============================= */
        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        return $results;
    }
}