<?php

namespace App\Http\Controllers;

use App\Models\{Query, QueryTerm, SuratMasuk, SuratKeluar};
use App\Services\{PreprocessingService, SimilarityService};
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $req)
    {
        $input = trim($req->q);
        if (!$input) return back();

        // 1. simpan query
        $query = Query::create(['user_input' => $input]);
        $tokens = PreprocessingService::tokenize($input);
        foreach ($tokens as $t => $tf) {
            QueryTerm::create([
                'query_id' => $query->id,
                'term'     => $t,
                'tf'       => $tf,
                'tfidf'    => $tf * log(1 + $tf), // sederhana
            ]);
        }

        // 2. ambil semua surat
        $surats = collect();
        foreach (SuratMasuk::cursor() as $s) {
            $vec = SimilarityService::vectorFromDB('masuk', $s->id);
            $surats->push((object)[
                'type' => 'masuk',
                'data' => $s,
                'vec'  => $vec,
                'jac'  => SimilarityService::jaccard($tokens, $vec),
                'cos'  => SimilarityService::cosine($tokens, $vec),
            ]);
        }
        foreach (SuratKeluar::cursor() as $s) {
            $vec = SimilarityService::vectorFromDB('keluar', $s->id);
            $surats->push((object)[
                'type' => 'keluar',
                'data' => $s,
                'vec'  => $vec,
                'jac'  => SimilarityService::jaccard($tokens, $vec),
                'cos'  => SimilarityService::cosine($tokens, $vec),
            ]);
        }

        // 3. urutkan
        $jacRank = $surats->sortByDesc('jac')->take(10);
        $cosRank = $surats->sortByDesc('cos')->take(10);

        return view('search.result', compact('jacRank', 'cosRank', 'input'));
    }
}