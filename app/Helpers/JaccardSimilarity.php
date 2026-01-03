<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class JaccardSimilarity
{
    public static function calculate(string $query, string $suratType): array
    {
        $queryTerms = array_unique(
            preg_split('/\s+/', strtolower(trim($query)))
        );

        if (!$queryTerms) return [];

        $rows = DB::table('surat_terms')
            ->where('surat_type', $suratType)
            ->whereIn('term', $queryTerms)
            ->select('surat_id', 'term')
            ->get();

        if ($rows->isEmpty()) return [];

        $docs = [];
        foreach ($rows as $r) {
            $docs[$r->surat_id][] = $r->term;
        }

        $result = [];
        foreach ($docs as $id => $terms) {
            $intersection = count(array_intersect($queryTerms, $terms));
            $union = count(array_unique(array_merge($queryTerms, $terms)));
            $score = $union ? $intersection / $union : 0;

            $result[] = [
                'surat_id' => $id,
                'similarity' => round($score, 4),
            ];
        }

        usort($result, fn($a,$b)=>$b['similarity']<=>$a['similarity']);
        return $result;
    }
}
