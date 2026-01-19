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
use App\Models\SuratTerm;
use App\Services\ConfusionMatrix;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\String\s;

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
        $cosineSimilaritiesRaw = CosineSimilarity::calculateCosineSimilarity($tfidfNormTable, count($documents));

        // Konversi format untuk ConfusionMatrix
        $cosineResultsForConfusion = [];
        foreach ($cosineSimilaritiesRaw as $item) {
            $docIndex = intval(substr($item['doc'], 1)) - 1;
            if (isset($documents[$docIndex])) {
                $doc = $documents[$docIndex];
                $cosineResultsForConfusion[] = [
                    'id' => $doc['id'],
                    'cosine' => $item['similarity'],
                    'tipe' => $doc['tipe'],
                    'isi' => $doc['isi'],
                    'tokens' => $doc['tokens'] ?? []
                ];
            }
        }

        /* ================= 7. JACCARD (GLOBAL) ================= */
        $querySet = array_unique($queryTokens);
        $jaccardSimilaritiesRaw = JaccardSimilarity::calculateJaccardSimilarity($documents, $querySet);

        // Konversi format untuk ConfusionMatrix
        $jaccardResultsForConfusion = [];
        foreach ($jaccardSimilaritiesRaw as $item) {
            $docIndex = intval(substr($item['doc'], 1)) - 1;
            if (isset($documents[$docIndex])) {
                $doc = $documents[$docIndex];
                $jaccardResultsForConfusion[] = [
                    'id' => $doc['id'],
                    'jaccard' => $item['jaccard'],
                    'tipe' => $doc['tipe'],
                    'isi' => $doc['isi'],
                    'tokens' => $doc['tokens'] ?? []
                ];
            }
        }

        /* ================= 6. FILTER HASIL (untuk display) ================= */
        if ($filter !== 'all') {
            $cosineSimilarities = array_filter($cosineSimilaritiesRaw, function ($row) use ($documents, $filter) {
                $docIndex = intval(substr($row['doc'], 1)) - 1;
                return ($documents[$docIndex]['tipe'] ?? null) === $filter;
            });
            
            $jaccardSimilarities = array_filter($jaccardSimilaritiesRaw, fn($j) => $j['tipe'] === $filter);
        } else {
            $cosineSimilarities = $cosineSimilaritiesRaw;
            $jaccardSimilarities = $jaccardSimilaritiesRaw;
        }

        /* ================= CONFUSION MATRIX ================= */
        $confusionMatrix = ConfusionMatrix::calculateForSearch(
            $cosineResultsForConfusion, 
            $jaccardResultsForConfusion, 
            $documents, 
            $query,
            0.1, // prediction threshold
            0.1  // ground truth threshold
        );

        /* ================= COMPARISON METRICS ================= */
        $comparisonMetrics = ConfusionMatrix::generateReport($confusionMatrix);

        /* ================= 10. DEBUG PREPROCESS ================= */
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
            'confusionMatrix',   
            'comparisonMetrics',
            
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
}