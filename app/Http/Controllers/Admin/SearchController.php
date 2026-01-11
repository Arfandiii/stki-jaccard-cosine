<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JaccardSimilarity;
use App\Helpers\CosineSimilarity;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Query;
use App\Models\QueryResult;
use Illuminate\Support\Facades\DB;
use App\Models\SuratTerm;
use App\Helpers\PreprocessingText;
use App\Helpers\QueryProcessor;


class SearchController extends Controller
{
    /* =======================
     * HALAMAN SEARCH
     * ======================= */
    public function index()
    {
        return view('admin.search.index');
    }

    /* ---------- pencarian utama ---------- */
    public function search(Request $r)
    {
        $raw   = trim($r->input('query',''));
        $type  = $r->input('letter_type','all'); // all|masuk|keluar
        $start = $r->input('start_date');
        $end   = $r->input('end_date');

        if (!$raw) return response()->json(['message'=>'Query kosong'], 422);

        /* 1. vektor query */
        $tokens = PreprocessingText::preprocessText($raw);
        $tf     = array_count_values($tokens);
        /* IDF on-the-fly */
        $N = DB::table('surat_terms')
            ->when($type !== 'all', fn($q) => $q->where('surat_type', $type))
            ->distinct('surat_id')->count('surat_id');
        $df = DB::table('surat_terms')
                ->when($type !== 'all', fn($q) => $q->where('surat_type', $type))
                ->selectRaw('term, COUNT(DISTINCT surat_id) as df')
                ->whereIn('term', array_keys($tf))
                ->groupBy('term')
                ->pluck('df', 'term');
        $idfList = $df->map(fn($v) => $v > 0 ? log10($N / $v) : 0.0);

        /* 2. TF-IDF norm query */
        $qVecRaw = [];
        foreach ($tf as $t => $f) $qVecRaw[$t] = (1 + log10($f)) * ($idfList[$t] ?? 0);
        $len = sqrt(array_sum(array_map(fn($v) => $v * $v, $qVecRaw)));
        $qVecNorm = [];
        if ($len > 0) foreach ($qVecRaw as $t => $w) $qVecNorm[$t] = $w / $len;

        /* 3. ambil dokumen yang punya minimal 1 term yang sama */
        $candidateIds = DB::table('surat_terms')
                        ->when($type !== 'all', fn($q) => $q->where('surat_type', $type))
                        ->when($start, fn($q) => $q->whereDate('created_at', '>=', $start))
                        ->when($end, fn($q) => $q->whereDate('created_at', '<=', $end))
                        ->whereIn('term', array_keys($tf))
                        ->distinct()
                        ->select('surat_type','surat_id')
                        ->get();

        /* 4. hitung similarity per dokumen */
        $results = [];
        foreach ($candidateIds as $c) {
            $rows = SuratTerm::where('surat_type', $c->surat_type)
                            ->where('surat_id', $c->surat_id)
                            ->get();
            $dVecNorm = $rows->pluck('tfidf_norm', 'term')->all();
            $dTerms   = $rows->pluck('term')->all();

            /* Cosine = dot product */
            $cos = 0;
            foreach ($qVecNorm as $t => $w) $cos += $w * ($dVecNorm[$t] ?? 0);

            /* Jaccard = |A ∩ B| / |A ∪ B| */
            $union = array_unique(array_merge($tokens, $dTerms));
            $inter = array_intersect($tokens, $dTerms);
            $jac   = count($union) ? count($inter) / count($union) : 0;

            /* ambil metadata surat */
            $surat = $c->surat_type === 'masuk'
                        ? SuratMasuk::find($c->surat_id)
                        : SuratKeluar::find($c->surat_id);
            if (!$surat) continue;

            $results[] = [
                'surat_type'    => $c->surat_type,
                'surat_id'      => $c->surat_id,
                'number'        => $surat->nomor_surat ?? '-',
                'title'         => $surat->perihal ?? '-',
                'date'          => $surat->tanggal_surat ?? '-',
                'cosine_score'  => $cos,
                'jaccard_score' => $jac,
            ];
        }

        /* 5. urutkan & potong 50 besar (batas awal) */
        usort($results, fn($a,$b) => $b['cosine_score'] <=> $a['cosine_score']);
        $cosine = array_slice($results, 0, 50);
        usort($results, fn($a,$b) => $b['jaccard_score'] <=> $a['jaccard_score']);
        $jaccard = array_slice($results, 0, 50);

        /* 6. statistik */
        $stats = [
            'total_documents' => DB::table('surat_terms')->when($type!=='all',fn($q)=>$q->where('surat_type',$type))->distinct('surat_id')->count('surat_id'),
            'surat_masuk'     => DB::table('surat_terms')->where('surat_type','masuk')->distinct('surat_id')->count('surat_id'),
            'surat_keluar'    => DB::table('surat_terms')->where('surat_type','keluar')->distinct('surat_id')->count('surat_id'),
        ];

        return response()->json([
            'cosine_results' => $cosine,
            'jaccard_results'=> $jaccard,
            'statistics'     => $stats,
            'execution_time' => microtime(true) - LARAVEL_START,
            'has_tfidf'      => DB::table('surat_terms')->where('tfidf','>',0)->exists(),
        ]);
    }

    /* ---------- preprocessing TF-IDF (jika belum) ---------- */
    public function preprocessTfidf(Request $r)
    {
        $type = $r->input('surat_type','all');
        $hit  = DB::table('surat_terms')
                ->when($type !== 'all', fn($q)=>$q->where('surat_type',$type))
                ->where('tfidf','>',0)->exists();
        if ($hit) return response()->json(['success'=>true,'message'=>'Sudah ada']);

        // hitung ulang TF-IDF
        \App\Helpers\TfidfService::calculate($type === 'all' ? 'masuk' : $type);
        if ($type === 'all' || $type === 'keluar') \App\Helpers\TfidfService::calculate('keluar');
        return response()->json(['success'=>true,'message'=>'Selesai']);
    }

    /* ---------- detail dokumen (untuk modal) ---------- */
    public function detail($type, $id)
    {
        $surat = $type === 'masuk'
                    ? SuratMasuk::findOrFail($id)
                    : SuratKeluar::findOrFail($id);
        return response()->json([
            'number' => $surat->nomor_surat ?? '-',
            'title'  => $surat->perihal ?? '-',
            'date'   => $surat->tanggal_surat ?? '-',
            'type'   => $type,
            'content'=> $surat->isi_surat ?? $surat->perihal,
            'jaccard_score'=> 0, // nanti diisi di client
            'cosine_score' => 0,
        ]);
    }

    // public function getDocuments()
    // {
    //     $ids = [1, 2, 3, 4, 5];
    //     $documents = SuratKeluar::whereIn('id', $ids)->get();

    //     return $documents->map(function ($item) {
    //         return [
    //             'nomor_surat' => $item->nomor_surat,
    //             'perihal' => $item->perihal,
    //             'tanggal_surat' => $item->tanggal_surat,
    //         ];
    //     })->toArray();
    // }

    // public function preprocessDocumentsDetailed($query)
    // {
    //     $documents = $this->getDocuments();

    //     foreach ($documents as &$document) {
    //         $preprocessingResult = PreprocessingText::preprocessTextDetailed($document['perihal']);
    //         $document['preprocessing'] = $preprocessingResult;
    //     }

    //     // Tambahkan dokumen query
    //     $queryPreprocessing = PreprocessingText::preprocessTextDetailed($query);

    //     $documents[] = [
    //         'nomor_surat' => 'QUERY',
    //         'perihal' => $query,
    //         'tanggal_surat' => null,
    //         'preprocessing' => $queryPreprocessing,
    //     ];
    //     // dd($documents);

    //     return $documents;
    // }

    // public function simple(Request $r)
    // {
    //     $raw = trim($r->input('query', ''));
    //     if (!$raw) {
    //         return view('admin.search.debug', ['query' => '', 'detail' => []]);
    //     }

    //     /* proses & eager-load */
    //     $q = QueryProcessor::process($raw);
    //     $q->load('queryTerms'); // hindari "pluck on null"

    //     /* ranking contoh (cosine) */
    //     $top = $this->rankDocs($q->queryTerms->pluck('tfidf_norm', 'term')->all(), 10);

    //     return view('admin.search.debug', [
    //         'query' => $raw,
    //         'detail' => [
    //             'query_terms' => $q->queryTerms,
    //             'top_docs'    => $top,
    //             'exec_time'   => $q->execution_time,
    //         ],
    //     ]);
    //     // $query = $request->input('query');
    //     // $documentsdetailed = $this->preprocessDocumentsDetailed($query);

    //     // return view('admin.search.debug', compact('query', 'documentsdetailed'));
    // }

    // private function rankDocs(array $qVec, int $take = 10)
    // {
    //     $docs = DB::table('surat_terms')
    //             ->select('surat_type', 'surat_id', 'term', 'tfidf_norm')
    //             ->get()
    //             ->groupBy(fn($row) => $row->surat_type.'-'.$row->surat_id);

    //     $scores = [];
    //     foreach ($docs as $key => $rows) {
    //         [$type, $id] = explode('-', $key);
    //         $dot = 0;
    //         foreach ($rows as $row) {
    //             $dot += ($qVec[$row->term] ?? 0) * $row->tfidf_norm;
    //         }
    //         $scores[] = ['surat_type' => $type, 'surat_id' => $id, 'score' => $dot];
    //     }
    //     usort($scores, fn($a,$b) => $b['score'] <=> $a['score']);
    //     return array_slice($scores, 0, $take);
    // }

    /* =======================
     * PROSES SEARCH
     * ======================= */
    // public function search(Request $request)
    // {
    //     $request->validate([
    //         'query'       => 'required|string|min:2',
    //         'letter_type' => 'nullable|in:all,masuk,keluar',
    //         'start_date'  => 'nullable|date',
    //         'end_date'    => 'nullable|date|after_or_equal:start_date',
    //     ]);

        
    //     $queryText  = $request->input('query');
    //     $type       = $request->letter_type ?? 'all';
    //     $startDate  = $request->start_date;
    //     $endDate    = $request->end_date;
        
    //     $startTime = microtime(true);
        
    //     $zeroTfidfCount = DB::table('surat_terms')
    //         ->where(function ($q) use ($type) {
    //             if ($type !== 'all') $q->where('surat_type', $type);
    //         })
    //         ->where('tfidf', 0)
    //         ->count();

    //     $hasTfidf = $zeroTfidfCount === 0;
        
    //     // === 0. Simpan query ===
    //     $query = Query::create([
    //         'query_text'    => $queryText,
    //         'letter_type'   => $type,
    //         'start_date'    => $startDate,
    //         'end_date'      => $endDate,
    //         'method'        => 'both', // karena kita hitung jaccard & cosine
    //     ]);

    //     // === 1. Simpan terms (opsional, kalau Anda ingin tracking TF-IDF) ===
    //     // Anda bisa panggil preprocessing di sini, lalu:
    //     // foreach ($terms as $term => $tfidf) {
    //     //     $query->terms()->create(['term' => $term, 'tfidf' => $tfidf]);
    //     // }

    //     // === 2. Hitung kedua metode ===
    //     foreach (['masuk', 'keluar'] as $tipe) {
    //         if ($type !== 'all' && $type !== $tipe) continue;

    //         // Jaccard
    //         foreach (JaccardSimilarity::calculate($queryText, $tipe) as $row) {
    //             $doc = $tipe === 'masuk' ? SuratMasuk::find($row['surat_id']) : SuratKeluar::find($row['surat_id']);
    //             if (!$doc) continue;

    //             QueryResult::create([
    //                 'query_id'   => $query->id,
    //                 'method'     => 'jaccard',
    //                 'surat_type' => $tipe,
    //                 'surat_id'   => $doc->id,
    //                 'score'      => $row['similarity'],
    //             ]);
    //         }

    //         // Cosine
    //         foreach (CosineSimilarity::calculate($queryText, $tipe) as $row) {
    //             $doc = $tipe === 'masuk' ? SuratMasuk::find($row['surat_id']) : SuratKeluar::find($row['surat_id']);
    //             if (!$doc) continue;

    //             QueryResult::create([
    //                 'query_id'   => $query->id,
    //                 'method'     => 'cosine',
    //                 'surat_type' => $tipe,
    //                 'surat_id'   => $doc->id,
    //                 'score'      => $row['similarity'],
    //             ]);
    //         }
    //     }

    //     $executionTime = round(microtime(true) - $startTime, 4);
    //     $query->update(['execution_time' => $executionTime]);

    //     // === 3. Ambil hasil untuk response ===
    //     $jaccardResults = $query->results()
    //         ->where('method', 'jaccard')
    //         ->where('surat_type', $type) // <-- tambahkan ini
    //         ->get()
    //         ->map(function ($r) {
    //             $doc = $r->surat_type === 'masuk'
    //                 ? SuratMasuk::find($r->surat_id)
    //                 : SuratKeluar::find($r->surat_id);
    //             return $this->mapResult($doc, $r->score, $r->surat_type);
    //         });

    //     $cosineResults = $query->results()
    //         ->where('method', 'cosine')
    //         ->where('surat_type', $type) // <-- tambahkan ini
    //         ->get()
    //         ->map(function ($r) {
    //             $doc = $r->surat_type === 'masuk'
    //                 ? SuratMasuk::find($r->surat_id)
    //                 : SuratKeluar::find($r->surat_id);
    //             return $this->mapResult($doc, $r->score, $r->surat_type);
    //         });

    //     return response()->json([
    //         'query_id'        => $query->id,
    //         'query'           => $queryText,
    //         'execution_time'  => $executionTime,
    //         'has_tfidf' => $hasTfidf,
    //         'statistics'      => [
    //             'total_documents' => SuratMasuk::count() + SuratKeluar::count(),
    //             'surat_masuk'     => SuratMasuk::count(),
    //             'surat_keluar'    => SuratKeluar::count(),
    //         ],
    //         'jaccard_results' => $jaccardResults,
    //         'cosine_results'  => $cosineResults,
    //     ]);
    // }
    // public function search(Request $request)
    // {
    //     $request->validate([
    //         'query'       => 'required|string|min:2',
    //         'letter_type' => 'nullable|in:all,masuk,keluar',
    //         'start_date'  => 'nullable|date',
    //         'end_date'    => 'nullable|date|after_or_equal:start_date',
    //     ]);

    //     $query      = $request->input('query');
    //     $type       = $request->letter_type ?? 'all';
    //     $startDate  = $request->start_date;
    //     $endDate    = $request->end_date;

    //     $startTime = microtime(true);

    //     $jaccardResults = [];
    //     $cosineResults  = [];

    //     /* =======================
    //      * SURAT MASUK
    //      * ======================= */
    //     if ($type === 'all' || $type === 'masuk') {

    //         foreach (JaccardSimilarity::calculate($query, 'masuk') as $row) {
    //             $doc = SuratMasuk::find($row['surat_id']);
    //             if (!$doc) continue;

    //             $jaccardResults[] = $this->mapResult($doc, $row['similarity'], 'masuk');
    //         }

    //         foreach (CosineSimilarity::calculate($query, 'masuk') as $row) {
    //             $doc = SuratMasuk::find($row['surat_id']);
    //             if (!$doc) continue;

    //             $cosineResults[] = $this->mapResult($doc, $row['similarity'], 'masuk');
    //         }
    //     }

    //     /* =======================
    //      * SURAT KELUAR
    //      * ======================= */
    //     if ($type === 'all' || $type === 'keluar') {

    //         foreach (JaccardSimilarity::calculate($query, 'keluar') as $row) {
    //             $doc = SuratKeluar::find($row['surat_id']);
    //             if (!$doc) continue;

    //             $jaccardResults[] = $this->mapResult($doc, $row['similarity'], 'keluar');
    //         }

    //         foreach (CosineSimilarity::calculate($query, 'keluar') as $row) {
    //             $doc = SuratKeluar::find($row['surat_id']);
    //             if (!$doc) continue;

    //             $cosineResults[] = $this->mapResult($doc, $row['similarity'], 'keluar');
    //         }
    //     }

    //     /* =======================
    //      * FILTER TANGGAL (AMAN)
    //      * ======================= */
    //     $filterByDate = function ($item) use ($startDate, $endDate) {
    //         if (empty($item['date'])) return true;
    //         if ($startDate && $item['date'] < $startDate) return false;
    //         if ($endDate && $item['date'] > $endDate) return false;
    //         return true;
    //     };

    //     $jaccardResults = array_values(array_filter($jaccardResults, $filterByDate));
    //     $cosineResults  = array_values(array_filter($cosineResults,  $filterByDate));

    //     /* =======================
    //      * SORTING DESC SCORE
    //      * ======================= */
    //     usort($jaccardResults, fn($a, $b) => $b['score'] <=> $a['score']);
    //     usort($cosineResults,  fn($a, $b) => $b['score'] <=> $a['score']);

    //     $executionTime = round(microtime(true) - $startTime, 4);

    //     return response()->json([
    //         'query'           => $query,
    //         'execution_time'  => $executionTime,
    //         'statistics'      => [
    //             'total_documents' => SuratMasuk::count() + SuratKeluar::count(),
    //             'surat_masuk'     => SuratMasuk::count(),
    //             'surat_keluar'    => SuratKeluar::count(),
    //         ],
    //         'jaccard_results' => $jaccardResults,
    //         'cosine_results'  => $cosineResults,
    //     ]);
    // }

    /* =======================
     * FORMAT DATA AGAR SERAGAM
     * ======================= */
    private function mapResult($doc, float $score, string $type): array
    {
        if ($type === 'masuk') {
            return [
                'surat_type'  => 'masuk',
                'surat_id'    => $doc->id,
                'score'       => $score,
                'number'      => $doc->nomor_surat,
                'date'        => optional($doc->tanggal_surat)->format('Y-m-d'),
                'title'       => $doc->perihal,
                'description' => $doc->isi_surat ?? '-',
            ];
        }

        // === SURAT KELUAR ===
        return [
            'surat_type'  => 'keluar',
            'surat_id'    => $doc->id,
            'score'       => $score,
            'number'      => $doc->nomor_surat ?? '-',
            'date'        => optional($doc->tanggal_surat)->format('Y-m-d'),
            'title'       => $doc->perihal,
            'description' => $doc->keterangan ?? '-',
        ];
    }

    public function tfidf(Request $request)
    {
        $type = $request->input('surat_type', 'all'); // all, masuk, keluar

        // Hitung IDF dulu
        $totalDocs = ($type === 'all' || $type === 'masuk' ? SuratMasuk::count() : 0) + ($type === 'all' || $type === 'keluar' ? SuratKeluar::count() : 0);

        if ($totalDocs === 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada dokumen.']);
        }

        // Ambil semua term unik
        $terms = SuratTerm::where(function ($q) use ($type) {
                if ($type !== 'all') $q->where('surat_type', $type);
            })
            ->pluck('term')
            ->unique();

        foreach ($terms as $term) {
            // DF = dokumen yang mengandung term ini
            $df = SuratTerm::where('term', $term)
                ->where(function ($q) use ($type) {
                    if ($type !== 'all') $q->where('surat_type', $type);
                })
                ->distinct('surat_id')
                ->count('surat_id');

            $idf = log10($totalDocs / $df);

            // Update TF-IDF
            SuratTerm::where('term', $term)
                ->where(function ($q) use ($type) {
                    if ($type !== 'all') $q->where('surat_type', $type);
                })
                ->update(['tfidf' => DB::raw("tf * $idf")]);
        }

        return response()->json(['success' => true]);
    }
}
