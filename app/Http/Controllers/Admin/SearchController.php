<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Query;
use App\Models\QueryTerm;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Services\ConfusionMatrix;
use App\Services\Similarity\CosineSimilarity;
use App\Services\Similarity\JaccardSimilarity;
use App\Services\TextPreprocessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /* ---------- HALAMAN UTAMA ---------- */
    public function index()
    {
        return view('admin.search.index');
    }

    public function search(Request $request)
    {
        $startTime = microtime(true);
        
        // CEK APAKAH INI SUBMIT FORM ATAU FIRST LOAD/PAGINATION
        $isFormSubmit = $request->has('query_text') || $request->has('letterType') || 
                        $request->has('startDate') || $request->has('endDate');

        // Ambil dan validasi query
        $query = trim($request->input('query_text', ''));
        
        // JIKA INI SUBMIT FORM DAN QUERY KOSONG
        if ($isFormSubmit && empty($query)) {
            return redirect()->route('admin.search.index')
                ->with('error', 'Kata kunci pencarian tidak boleh kosong')
                ->withInput($request->except('query_text'));
        }
        
        // JIKA QUERY VALID, CLEAR ERROR LAMA
        if (!empty($query)) {
            session()->forget('error');
        }
        
        // JIKA INI PAGINATION ATAU FIRST LOAD TANPA QUERY
        if (!$isFormSubmit && empty($query)) {
            // Tampilkan halaman kosong tanpa error
            return view('admin.search.index', [
                'query' => '',
                'letterType' => 'all',
                'startDate' => '',
                'endDate' => '',
                'cosineResults' => [],
                'jaccardResults' => [],
                'cosinePaginator' => null,
                'jaccardPaginator' => null,
                'cosineTotal' => 0,
                'jaccardTotal' => 0,
                'totalTime' => 0,
                'cosineTime' => 0,
                'jaccardTime' => 0,
                'preprocessingTime' => 0,
                'totalSuratUnik' => 0,
                'suratMasukUnik' => 0,
                'suratKeluarUnik' => 0,
                'totalJaccard' => 0,
                'totalCosine' => 0,
                'jaccardMasuk' => 0,
                'jaccardKeluar' => 0,
                'cosineMasuk' => 0,
                'cosineKeluar' => 0,
                'avgCosine' => 0,
                'avgJaccard' => 0,
                'confusionMatrix' => null,
                'comparisonMetrics' => null
            ]);
        }
        
        // Simpan parameter pencarian di session
        $request->session()->put('search_params', [
            'query_text' => $query,
            'letterType' => $request->input('letterType'),
            'startDate'  => $request->input('startDate'),
            'endDate'    => $request->input('endDate')
        ]);
        
        // Ambil parameter dari session jika ada (untuk pagination)
        $sessionParams = $request->session()->get('search_params', []);
        
        $query      = $request->input('query_text', $sessionParams['query_text'] ?? '');
        $letterType = $request->input('letterType', $sessionParams['letterType'] ?? 'all');
        $startDate  = $request->input('startDate', $sessionParams['startDate'] ?? '');
        $endDate    = $request->input('endDate', $sessionParams['endDate'] ?? '');

        // 1. Preprocess query - Catat waktu preprocessing
        $preprocessingStart = microtime(true);
        $queryData  = $this->preprosesQuery($query, $letterType, $startDate, $endDate);
        $queryTfidfNorm = $queryData['tfidfNorm'];
        $queryTerms = $queryData['terms'];
        $preprocessingTime = round(microtime(true) - $preprocessingStart, 3);

        // 2. Get Cosine Similarity Results - Catat waktu Cosine
        $cosineStart = microtime(true);
        $allCosineResults = CosineSimilarity::getCosineResults($queryTfidfNorm, $letterType, $startDate, $endDate);
        $cosineTime = round(microtime(true) - $cosineStart, 3);

        // 3. Get Jaccard Similarity Results - Catat waktu Jaccard
        $jaccardStart = microtime(true);
        $allJaccardResults = JaccardSimilarity::getWeightedJaccardResults($queryTfidfNorm, $letterType, $startDate, $endDate);
        $jaccardTime = round(microtime(true) - $jaccardStart, 3);
        
        /* ================= CONFUSION MATRIX (hanya jika ada hasil) ================= */
        $confusionMatrix = null;
        $comparisonMetrics = null;
        
        if (!empty($allCosineResults) || !empty($allJaccardResults)) {
            // Catat waktu confusion matrix
            $confusionStart = microtime(true);
            
            // Ambil semua dokumen yang memenuhi filter untuk ground truth
            $allDocuments = $this->getAllFilteredDocuments($letterType, $startDate, $endDate);
            
            // Gunakan service ConfusionMatrix
            $confusionMatrix = ConfusionMatrix::calculateForSearch(
                $allCosineResults, 
                $allJaccardResults, 
                $allDocuments, 
                $query
            );
            
            // Generate report untuk ditampilkan
            $comparisonMetrics = ConfusionMatrix::generateReport($confusionMatrix);
            
            $confusionTime = round(microtime(true) - $confusionStart, 3);
        }
        
        // 4. Pagination settings - GUNAKAN PARAMETER BERBEDA
        $perPage = 5; // Hasil per halaman
        $cosinePage = $request->input('cosine_page', 1);  // Parameter khusus Cosine
        $jaccardPage = $request->input('jaccard_page', 1); // Parameter khusus Jaccard
        
        // 5. Paginate Cosine Results
        $cosineCollection = collect($allCosineResults);
        $cosinePaginated = $cosineCollection->forPage($cosinePage, $perPage)->values();
        $cosineResults = $cosinePaginated->all();
        $cosineTotal = $cosineCollection->count();
        
        // 6. Paginate Jaccard Results  
        $jaccardCollection = collect($allJaccardResults);
        $jaccardPaginated = $jaccardCollection->forPage($jaccardPage, $perPage)->values();
        $jaccardResults = $jaccardPaginated->all();
        $jaccardTotal = $jaccardCollection->count();
        
        // 7. Calculate total time
        $totalTime = round(microtime(true) - $startTime, 3);
        
        // Gabungkan semua hasil dan ambil ID surat unik
        $allResults = array_merge($allCosineResults, $allJaccardResults);
        $resultsCount = count(array_unique(array_column($allResults, 'id')));
        
        // Ambil ID surat unik (tanpa duplikat)
        $uniqueIds = collect($allResults)->pluck('id')->unique();
        $totalSuratUnik = $uniqueIds->count();
        
        // Ambil surat unik untuk menghitung tipe
        $uniqueResults = collect($allResults)->unique('id');
        
        // Hitung jumlah surat masuk dan keluar dari surat unik
        $suratMasukUnik = $uniqueResults->where('tipe', 'masuk')->count();
        $suratKeluarUnik = $uniqueResults->where('tipe', 'keluar')->count();
        
        // Hitung distribusi per algoritma
        $jaccardMasuk = collect($allJaccardResults)->where('tipe', 'masuk')->count();
        $jaccardKeluar = collect($allJaccardResults)->where('tipe', 'keluar')->count();
        $cosineMasuk = collect($allCosineResults)->where('tipe', 'masuk')->count();
        $cosineKeluar = collect($allCosineResults)->where('tipe', 'keluar')->count();
        
        // Jumlah hasil per algoritma
        $totalJaccard = $jaccardTotal;
        $totalCosine = $cosineTotal;
        
        // Average scores
        $avgCosine = $cosineTotal > 0 
            ? collect($allCosineResults)->avg('cosine') 
            : 0;
        
        $avgJaccard = $jaccardTotal > 0 
            ? collect($allJaccardResults)->avg('jaccard') 
            : 0;

        // 8. UPDATE query di database dengan execution time dan results
        if ($queryData['queryModel'] && $isFormSubmit) {
            try {
                // Ini query baru, update dengan hasil pencarian
                $queryData['queryModel']->update([
                    'execution_time' => $totalTime,
                    'cosine_time' => $cosineTime,
                    'jaccard_time' => $jaccardTime,
                    'preprocessing_time' => $preprocessingTime,
                    'results_count' => $resultsCount,
                    'avg_cosine_score' => $avgCosine,
                    'avg_jaccard_score' => $avgJaccard
                ]);
            } catch (\Exception $e) {
                Log::warning('Gagal update query stats: ' . $e->getMessage());
            }
        }

        // 9. Create paginator instances untuk view dengan PARAMETER YANG BERBEDA
        $queryParams = [
            'query_text' => $query,
            'letterType' => $letterType,
            'startDate'  => $startDate,
            'endDate'    => $endDate
        ];
        
        // Paginator untuk Cosine dengan parameter cosine_page
        $cosinePaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $cosineResults,
            $cosineTotal,
            $perPage,
            $cosinePage,
            [
                'path' => $request->url(),
                'pageName' => 'cosine_page', // Nama parameter khusus
                'query' => array_merge($queryParams, $request->except('cosine_page'))
            ]
        );
        
        // Paginator untuk Jaccard dengan parameter jaccard_page
        $jaccardPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $jaccardResults,
            $jaccardTotal,
            $perPage,
            $jaccardPage,
            [
                'path' => $request->url(),
                'pageName' => 'jaccard_page', // Nama parameter khusus
                'query' => array_merge($queryParams, $request->except('jaccard_page'))
            ]
        );

        return view('admin.search.index', compact(
            'query',
            'letterType',
            'startDate',
            'endDate',
            // Results dengan pagination
            'cosineResults',
            'jaccardResults',
            // Paginators
            'cosinePaginator',
            'jaccardPaginator',
            // Totals
            'cosineTotal',
            'jaccardTotal',
            // Waktu eksekusi
            'totalTime',
            'cosineTime',
            'jaccardTime',
            'preprocessingTime',
            // STATISTIK SURAT UNIK
            'totalSuratUnik',
            'suratMasukUnik',
            'suratKeluarUnik',
            // INFORMASI ALGORITMA
            'totalJaccard',
            'totalCosine',
            'jaccardMasuk',
            'jaccardKeluar',
            'cosineMasuk',
            'cosineKeluar',
            // SKOR RATA-RATA
            'avgCosine',
            'avgJaccard',
            // CONFUSION MATRIX
            'confusionMatrix',
            'comparisonMetrics'
        ));
    }

    /* ================= METHOD-METHOD BARU UNTUK CONFUSION MATRIX ================= */
    
    /**
     * Ambil semua dokumen yang memenuhi filter
     */
    private function getAllFilteredDocuments($letterType, $startDate, $endDate)
    {
        $documents = [];
        
        // Ambil surat masuk
        if (!$letterType || $letterType == 'all' || $letterType == 'masuk') {
            $query = SuratMasuk::query();
            
            if ($startDate) {
                $query->whereDate('tanggal_surat', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_surat', '<=', $endDate);
            }
            
            $suratMasuk = $query->get();
            
            // Ambil semua tokens untuk surat masuk sekaligus
            $masukIds = $suratMasuk->pluck('id')->toArray();
            $masukTokens = [];
            
            if (!empty($masukIds)) {
                $masukTokens = DB::table('surat_terms')
                    ->where('surat_type', 'masuk')
                    ->whereIn('surat_id', $masukIds)
                    ->select('surat_id', DB::raw('GROUP_CONCAT(term SEPARATOR ",") as terms'))
                    ->groupBy('surat_id')
                    ->pluck('terms', 'surat_id')
                    ->toArray();
            }
            
            foreach ($suratMasuk as $item) {
                $tokens = [];
                if (isset($masukTokens[$item->id])) {
                    $tokens = explode(',', $masukTokens[$item->id]);
                }
                
                $documents[] = [
                    'id' => 'SM-' . $item->id,
                    'tipe' => 'masuk',
                    'isi' => $item->perihal,
                    'tokens' => $tokens
                ];
            }
        }
        
        // Ambil surat keluar
        if (!$letterType || $letterType == 'all' || $letterType == 'keluar') {
            $query = SuratKeluar::query();
            
            if ($startDate) {
                $query->whereDate('tanggal_surat', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_surat', '<=', $endDate);
            }
            
            $suratKeluar = $query->get();
            
            // Ambil semua tokens untuk surat keluar sekaligus
            $keluarIds = $suratKeluar->pluck('id')->toArray();
            $keluarTokens = [];
            
            if (!empty($keluarIds)) {
                $keluarTokens = DB::table('surat_terms')
                    ->where('surat_type', 'keluar')
                    ->whereIn('surat_id', $keluarIds)
                    ->select('surat_id', DB::raw('GROUP_CONCAT(term SEPARATOR ",") as terms'))
                    ->groupBy('surat_id')
                    ->pluck('terms', 'surat_id')
                    ->toArray();
            }
            
            foreach ($suratKeluar as $item) {
                $tokens = [];
                if (isset($keluarTokens[$item->id])) {
                    $tokens = explode(',', $keluarTokens[$item->id]);
                }
                
                $documents[] = [
                    'id' => 'SK-' . $item->id,
                    'tipe' => 'keluar',
                    'isi' => $item->perihal,
                    'tokens' => $tokens
                ];
            }
        }
        
        return $documents;
    }

    
    public function preprosesQuery($query, $letterType = null, $startDate = null, $endDate = null, 
                                $executionTime = null, $cosineTime = null, $jaccardTime = null, 
                                $preprocessingTime = null, $resultsCount = null, 
                                $avgCosine = null, $avgJaccard = null)
    {
        // Validasi lagi untuk safety
        $cleanQuery = trim($query);
        if (empty($cleanQuery)) {
            return [
                'queryModel' => null,
                'terms'      => [],
                'tf'         => [],
                'idf'        => [],
                'tfidf'      => [],
                'tfidfNorm'  => [],
                'totalDocs'  => 0,
            ];
        }
        
        // Normalize parameters untuk konsistensi
        $normalizedLetterType = $letterType ?: 'all';
        $normalizedStartDate = $startDate ? date('Y-m-d', strtotime($startDate)) : null;
        $normalizedEndDate = $endDate ? date('Y-m-d', strtotime($endDate)) : null;
        
        // CEK DULU: Apakah query ini sudah ada di database dengan parameter yang sama?
        $queryModel = Query::where('query_text', $cleanQuery)
            ->where('letter_type', $normalizedLetterType)
            ->when($normalizedStartDate, function($q) use ($normalizedStartDate) {
                return $q->where('start_date', $normalizedStartDate);
            }, function($q) {
                return $q->whereNull('start_date');
            })
            ->when($normalizedEndDate, function($q) use ($normalizedEndDate) {
                return $q->where('end_date', $normalizedEndDate);
            }, function($q) {
                return $q->whereNull('end_date');
            })
            ->first();
        
        // Jika belum ada, baru CREATE dengan semua kolom waktu
        if (!$queryModel) {
            try {
                $queryModel = Query::create([
                    'query_text' => $cleanQuery,
                    'letter_type' => $normalizedLetterType,
                    'start_date' => $normalizedStartDate,
                    'end_date' => $normalizedEndDate,
                    'execution_time' => 0,
                    'cosine_time' => 0,
                    'jaccard_time' => 0,
                    'preprocessing_time' => 0,
                    'results_count' => 0,
                    'avg_cosine_score' => 0,
                    'avg_jaccard_score' => 0
                ]);
            } catch (\Exception $e) {
                $queryModel = null;
                Log::warning('Gagal menyimpan query: ' . $e->getMessage());
            }
        }
        // Jika query sudah ada, gunakan yang existing
        // Tidak perlu create baru
        
        // 2. Pre-processing (tetap perlu dilakukan untuk tfidf)
        $queryTokens = TextPreprocessor::preprocessText($cleanQuery);
        $queryTF     = array_count_values($queryTokens);
        $terms       = array_keys($queryTF);
        $N           = SuratMasuk::count() + SuratKeluar::count();
        
        // 3. IDF dari tabel surat_terms (baca DB)
        $idfMap = $this->getIdfMap($terms, $N);

        // 4. TF-IDF query + normalisasi
        $queryTfidf = [];
        foreach ($terms as $t) {
            $tfWeight = ($queryTF[$t] ?? 0) > 0 ? 1 + log10($queryTF[$t]) : 0;
            $idf      = $idfMap[$t] ?? log10($N / 1); // df=1 fallback
            $queryTfidf[$t] = $tfWeight * $idf;
        }
        $len = sqrt(array_sum(array_map(fn($v) => $v * $v, $queryTfidf)));
        if ($len) {
            $queryTfidfNorm = array_map(fn($v) => round($v / $len, 6), $queryTfidf);
        } else {
            $queryTfidfNorm = [];
        }

        // 5. Simpan ke query_terms (opsional) - hanya jika queryModel berhasil dibuat
        if ($queryModel) {
            foreach ($queryTfidfNorm as $term => $w) {
                try {
                    QueryTerm::updateOrCreate(
                        ['query_id' => $queryModel->id, 'term' => $term],
                        [
                            'tf'            => $queryTF[$term] ?? 0,
                            'idf'           => $idfMap[$term] ?? log10($N / 1),
                            'tfidf'         => $queryTfidf[$term],
                            'tfidf_norm'    => $w
                        ]
                    );
                } catch (\Exception $e) {
                    Log::warning('Gagal menyimpan query term: ' . $e->getMessage());
                }
            }
        }

        return [
            'queryModel' => $queryModel,
            'terms'      => $terms,
            'tf'         => $queryTF,
            'idf'        => $idfMap,
            'tfidf'      => $queryTfidf,
            'tfidfNorm'  => $queryTfidfNorm,
            'totalDocs'  => $N,
        ];
    }

    /* ---------- AMBIL IDF DARI TABEL surat_terms ---------- */
    private function getIdfMap(array $terms, int $totalDocs): array
    {
        $key = 'idf_map:' . md5(implode('|', $terms));
        return Cache::remember($key, now()->addMinutes(10), function () use ($terms, $totalDocs) {
            return DB::table('surat_terms')
                ->select('term', DB::raw('COUNT(DISTINCT CONCAT(surat_type,"-",surat_id)) as df'))
                ->whereIn('term', $terms)
                ->groupBy('term')
                ->pluck('df', 'term')
                ->map(function ($df) use ($totalDocs) {
                    return $df > 0 ? round(log10($totalDocs / $df), 4) : round(log10($totalDocs / 1), 4);
                })
                ->toArray();
        });
    }
}