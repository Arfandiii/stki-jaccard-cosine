<?php

namespace App\Services\Similarity;

use App\Models\SuratTerm;

class JaccardSimilarity
{
        /**
     * Hitung Jaccard similarity untuk SEMUA dokumen vs query
     * @param array $documents  ['tokens'=>[...], ...]
     * @param array $querySet   hasil array_unique(preprocessText)
     * @return array [ ['doc'=>'D1','jaccard'=>0.25], ... ]
     */
    public static function calculateJaccardSimilarity($documents, $querySet)
    {
        $jaccard = [];

        foreach ($documents as $idx => $d) {
            $docSet = array_unique($d['tokens']);      // set kata dokumen
            $inter  = count(array_intersect($docSet, $querySet));
            $union  = count(array_unique(array_merge($docSet, $querySet)));

            $jac    = $union > 0 ? round($inter / $union, 4) : 0;

            $jaccard[] = [
                'doc'      => 'D'.($idx + 1),
                'jaccard'  => $jac,
                'tipe'     => $d['tipe'] ?? 'unknown'
            ];
        }

        // urutkan besar→kecil
        usort($jaccard, fn($a,$b) => $b['jaccard'] <=> $a['jaccard']);
        return array_filter($jaccard, fn($j) => $j['jaccard'] > 0);
    }
}