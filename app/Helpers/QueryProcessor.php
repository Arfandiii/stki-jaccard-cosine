<?php

namespace App\Helpers;

use App\Models\Query;
use App\Models\QueryTerm;
use Illuminate\Support\Facades\DB;

class QueryProcessor
{
    public static function process(string $raw, ?string $type=null, ?string $start=null, ?string $end=null, string $method='cosine'): Query
    {
        $time = microtime(true);

        /* 1. preprocessing */
        $tokens = PreprocessingText::preprocessText($raw); // array kata
        if (!$tokens) $tokens = [];

        /* 2. TF */
        $tf = array_count_values($tokens);

        /* 3. IDF on-the-fly dari surat_terms (seluruh korpus) */
        $N = DB::table('surat_terms')->distinct('surat_id')->count('surat_id');
        $df = DB::table('surat_terms')
                ->selectRaw('term, COUNT(DISTINCT surat_id) as df')
                ->whereIn('term', array_keys($tf))
                ->groupBy('term')
                ->pluck('df', 'term');

        $idfList = $df->map(fn($v) => $v > 0 ? log10($N / $v) : 0.0);

        /* 4. TF-IDF raw + norm */
        $vecRaw = [];
        foreach ($tf as $t => $f) {
            $vecRaw[$t] = (1 + log10($f)) * ($idfList[$t] ?? 0);
        }
        $len = sqrt(array_sum(array_map(fn($v) => $v * $v, $vecRaw)));
        $vecNorm = [];
        if ($len > 0) {
            foreach ($vecRaw as $t => $w) $vecNorm[$t] = $w / $len;
        } else {
            $vecNorm = $vecRaw;
        }

        /* 5. simpan query master */
        $q = Query::create([
            'query_text'     => $raw,
            'letter_type'    => $type,
            'start_date'     => $start,
            'end_date'       => $end,
            'execution_time' => null,
            'method'         => $method,
        ]);

        /* 6. mass-insert term */
        $insert = [];
        foreach ($tf as $t => $f) {
            $insert[] = [
                'query_id'    => $q->id,
                'term'        => $t,
                'tf'          => $f,
                'idf'         => $idfList[$t] ?? 0,
                'tfidf'       => $vecRaw[$t] ?? 0,
                'tfidf_norm'  => $vecNorm[$t] ?? 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }
        if ($insert) QueryTerm::insert($insert);

        /* 7. exec time */
        $q->update(['execution_time' => microtime(true) - $time]);

        return $q;
    }
}