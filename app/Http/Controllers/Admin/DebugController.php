<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Query;
use App\Models\QueryTerm;
use App\Services\TextPreprocessor;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use App\Services\Similarity\CosineSimilarity;
use App\Services\Similarity\JaccardSimilarity;
use App\Services\TfidfService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DebugController extends Controller
{
    /* ---------- HALAMAN UTAMA DEBUG ---------- */
    public function index()
    {
        return view('admin.search.debug');
    }

    
    /* ---------- 10 SURAT MASUK ---------- */
    private function getSuratMasukDocuments()
    {
        return SuratMasuk::take(10)->get()->map(fn($item) => [
            'id'      => 'SM-' . $item->id,
            'tipe'    => 'masuk',
            'nomor'   => $item->nomor_surat,
            'tanggal' => $item->tanggal_surat,
            'asal'    => $item->asal_surat,
            'isi'     => trim($item->perihal),
        ])->toArray();
    }

    /* ---------- 10 SURAT KELUAR ---------- */
    private function getSuratKeluarDocuments()
    {
        return SuratKeluar::take(10)->get()->map(fn($item) => [
            'id'      => 'SK-' . $item->id,
            'tipe'    => 'keluar',
            'nomor'   => $item->nomor_surat,
            'tanggal' => $item->tanggal_surat,
            'tujuan'  => $item->tujuan_surat,
            'isi'     => trim($item->perihal),
        ])->toArray();
    }

    /* ---------- PRE-PROCESSING + TF-IDF 20 DOKUMEN ---------- */
    public function search(Request $request)
    {
        $query  = $request->input('query', '');
        $filter = strtolower($request->input('filter', 'all'));

        if (!$query) {
            return view('admin.search.debug');
        }

        /* ================= 1. AMBIL SEMUA DOKUMEN ================= */
        $documentsRaw = array_merge(
            $this->getSuratMasukDocuments(),
            $this->getSuratKeluarDocuments()
        );

        /* ================= 2. PREPROCESS DOKUMEN ================= */
        $documents = [];
        foreach ($documentsRaw as $d) {
            $documents[] = [
                'id'     => $d['id'],
                'tipe'   => $d['tipe'],
                'isi'    => $d['isi'],
                'tokens' => TextPreprocessor::preprocessText($d['isi']),
            ];
        }

        /* ================= 3. PREPROCESS QUERY ================= */
        $queryTokens = TextPreprocessor::preprocessText($query);

        /* ================= 4. HITUNG TF-IDF GLOBAL ================= */
        $tfTable       = TfidfService::calculateTermFrequencies($documents, $queryTokens);
        $tfWeightTable = TfidfService::calculateTFWeight($tfTable, count($documents));
        $idfTable      = TfidfService::calculateIDF($tfTable, count($documents));
        $tfidfTable    = TfidfService::calculateTFIDF($tfWeightTable, $idfTable, count($documents));

        $tfidfNorm = TfidfService::normalizeTFIDF($tfidfTable, count($documents));
        $tfidfNormTable = $tfidfNorm['normalized'];

        /* ================= 5. COSINE SEMUA DOKUMEN ================= */
        $cosineSimilarities = CosineSimilarity::calculateCosineSimilarity($tfidfNormTable, count($documents));

        /* ================= 6. FILTER HASIL (Bukan hitung ulang!) ================= */
        if ($filter !== 'all') {
            $cosineSimilarities = array_filter($cosineSimilarities, function ($row) use ($documents, $filter) {
                $docIndex = intval(substr($row['doc'], 1)) - 1;
                return ($documents[$docIndex]['tipe'] ?? null) === $filter;
            });
        }

        /* ================= 7. JACCARD (GLOBAL) ================= */
        $querySet = array_unique($queryTokens);
        $jaccardSimilarities = JaccardSimilarity::calculateJaccardSimilarity($documents, $querySet);

        if ($filter !== 'all') {
            $jaccardSimilarities = array_filter($jaccardSimilarities, fn($j) => $j['tipe'] === $filter);
        }

        /* ================= 8. DEBUG PREPROCESS ================= */
        $documentsdetailed = $this->preprocessSuratDocumentsDetailed($query);

        return view('admin.search.debug', compact(
            'query',
            'queryTokens',
            'filter',
            'documents',
            'tfTable',
            'tfWeightTable',
            'idfTable',
            'tfidfTable',
            'tfidfNormTable',
            'cosineSimilarities',
            'jaccardSimilarities',
            'documentsdetailed',
        ));
    }

    /* ---------- PREPROCESS + QUERY (detail) ---------- */
    private function preprocessSuratDocumentsDetailed($query)
    {
        $docs = array_merge($this->getSuratMasukDocuments(), $this->getSuratKeluarDocuments());
        foreach ($docs as &$d) {
            $d['preprocessing'] = TextPreprocessor::preprocessTextDetailed($d['isi']);
        }
        $docs[] = [
            'id'            => 'QUERY',
            'tipe'          => 'query',
            'nomor'         => '-',
            'tanggal'       => '-',
            'isi'           => $query,
            'preprocessing' => TextPreprocessor::preprocessTextDetailed($query),
        ];
        return $docs;
    }


    public static function preprocessQuery(string $query,
    ?string $letterType = null,
    ?string $startDate = null,
    ?string $endDate = null,
    string $method = 'both')
    {
        $start = microtime(true);   

        // 1. Simpan query ke dalam tabel queries
        $queryModel = Query::create([
            'query_text'  => $query,
            'letter_type' => $letterType,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'method'      => $method,
        ]);

        // 2. Preprocess query
        $tokens   = TextPreprocessor::preprocessText($query);
        $queryTF  = array_count_values($tokens);
        $terms    = array_keys($queryTF);

        // 3. Hitung IDF (cached)
        $totalDocs = SuratMasuk::count() + SuratKeluar::count();
        $key       = 'idf_map:' . md5(implode('|', $terms));
        $idfMap    = Cache::remember($key, Carbon::now()->addMinutes(10), function () use ($terms, $totalDocs) {
            return DB::table('surat_terms')
                ->select('term', DB::raw('COUNT(DISTINCT surat_id) as docs'))
                ->whereIn('term', $terms)
                ->groupBy('term')
                ->pluck('docs', 'term')
                ->map(fn($df) => $df > 0 ? round(log10($totalDocs / $df), 4) : 0)
                ->toArray();
        });

        // 4. Hitung TF-IDF & simpan ke query_terms
        $queryTFIDF = [];
        DB::transaction(function () use ($queryModel, $queryTF, $idfMap, &$queryTFIDF) {
            foreach ($queryTF as $term => $count) {
                $tfWeight       = $count > 0 ? round(1 + log10($count), 4) : 0;
                $idf            = $idfMap[$term] ?? 0;
                $tfidf          = round($tfWeight * $idf, 4);
                $queryTFIDF[$term] = $tfidf;

                QueryTerm::updateOrCreate(
                    ['query_id' => $queryModel->id, 'term' => $term],
                    ['tf' => $count, 'tfidf' => $tfidf]
                );
            }
        });

        // 5. Hitung & update execution_time (dalam ms)
        $timeMs = round((microtime(true) - $start) * 1000, 2);
        $queryModel->update(['execution_time' => $timeMs]);

        return [
            'queryModel' => $queryModel,
            'queryTFIDF' => $queryTFIDF,
        ];
    }
}