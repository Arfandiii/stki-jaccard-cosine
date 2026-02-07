<?php

namespace App\Services\Similarity;

use App\Models\SuratTerm;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;

class JaccardSimilarity
{

    /**
     * Hitung Jaccard similarity untuk set dokumen dan query
     * Mengembalikan array yang berisi dokumen dan nilai Jaccard similarity-nya
     * Nilai Jaccard similarity dihitung menggunakan formula: J(A,B) = |A ∩ B| / |A ∪ B|
     * Hanya dokumen yang memiliki minimal 1 term yang sama dengan query yang dihitung
     * Dokumen yang tidak memiliki term query tidak dihitung
     * Hasil akan diurutkan dari besar ke kecil
     * Dokumen yang tidak memiliki nilai Jaccard similarity (0) akan dihilangkan
     *
     * @param array $documents Array of documents, where each document is an array of 'tokens' and 'tipe'
     * @param array $querySet Set of query terms
     * @return array Array of documents with Jaccard similarity values
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

    
    /**
     * Hitung WEIGHTED Jaccard similarity menggunakan TF-IDF weights
     * Formula: sum(min(w_q, w_d)) / sum(max(w_q, w_d))
     */
    public static function calculateWeightedJaccard(array $queryVec, array $docVec): float
    {
        // Pastikan semua term dari kedua sisi ada
        $allTerms = array_unique(array_merge(
            array_keys($queryVec), 
            array_keys($docVec)
        ));
        
        $intersectionWeight = 0;
        $unionWeight = 0;
        
        foreach ($allTerms as $term) {
            $wQuery = $queryVec[$term] ?? 0;
            $wDoc = $docVec[$term] ?? 0;
            
            $intersectionWeight += min($wQuery, $wDoc);
            $unionWeight += max($wQuery, $wDoc);
        }
        
        return $unionWeight > 0 ? round($intersectionWeight / $unionWeight, 4) : 0;
    }
    
    /**
     * Hitung BINARY Jaccard (untuk perbandingan)
     */
    public static function calculateBinaryJaccard(array $queryTerms, array $docTerms): float
    {
        $querySet = array_unique($queryTerms);
        $docSet = array_unique($docTerms);
        
        $intersection = count(array_intersect($querySet, $docSet));
        $union = count(array_unique(array_merge($querySet, $docSet)));
        
        return $union > 0 ? $intersection / $union : 0;
    }

    /**
     * Get Weighted Jaccard Results menggunakan TF-IDF weights
     */
    public static function getWeightedJaccardResults(array $queryVec, ?string $letterType, ?string $start, ?string $end): array
    {
        if (empty($queryVec)) {
            return [];
        }

        $suratIds = self::getFilteredSuratIds($letterType, $start, $end);
        
        if (empty($suratIds['masuk']) && empty($suratIds['keluar'])) {
            return [];
        }
        
        $results = [];
        
        // Proses surat masuk
        if (!empty($suratIds['masuk'])) {
            $results = array_merge($results, self::processSuratTypeWeighted(
                'masuk', 
                $suratIds['masuk'], 
                $queryVec
            ));
        }
        
        // Proses surat keluar
        if (!empty($suratIds['keluar'])) {
            $results = array_merge($results, self::processSuratTypeWeighted(
                'keluar', 
                $suratIds['keluar'], 
                $queryVec
            ));
        }
        
        usort($results, fn($a, $b) => $b['jaccard'] <=> $a['jaccard']);
        return $results;
    }

    /**
     * Proses surat dengan Weighted Jaccard (FIXED VERSION)
     */
    private static function processSuratTypeWeighted(string $type, array $ids, array $queryVec): array
    {
        $results = [];
        
        // Ambil SEMUA terms tanpa filter
        $terms = SuratTerm::where('surat_type', $type)
            ->whereIn('surat_id', $ids)
            ->get(['surat_id', 'term', 'tfidf_norm'])
            ->groupBy('surat_id');
            
        $model = $type === 'masuk' ? SuratMasuk::class : SuratKeluar::class;
        $details = $model::whereIn('id', $ids)->get()->keyBy('id');
        
        foreach ($terms as $id => $docTerms) {
            if (!isset($details[$id])) continue;
            
            // Buat document vector untuk semua term
            $docVec = [];
            foreach ($docTerms as $term) {
                $docVec[$term->term] = (float) $term->tfidf_norm;
            }
            
            // Pastikan semua term query ada (dengan weight 0 jika tidak ada)
            foreach (array_keys($queryVec) as $qTerm) {
                if (!isset($docVec[$qTerm])) {
                    $docVec[$qTerm] = 0;
                }
            }
            
            $jaccard = self::calculateWeightedJaccard($queryVec, $docVec);
            
            if ($jaccard > 0.05) { // Threshold lebih rendah karena weighted
                $detail = $details[$id];
                
                $result = [
                    'id'      => ($type === 'masuk' ? 'SM-' : 'SK-') . $detail->id,
                    'nomor'   => $detail->nomor_surat,
                    'tanggal' => $detail->tanggal_surat,
                    'isi'     => $detail->perihal,
                    'jaccard' => $jaccard,
                    'tipe'    => $type,
                    'weighted' => true,
                    'matched_terms' => array_intersect(array_keys($queryVec), array_keys($docVec)),
                    'query_weight_sum' => array_sum($queryVec),
                    'doc_weight_sum' => array_sum($docVec)
                ];
                
                if ($type === 'masuk') {
                    $result['tanggal_terima'] = $detail->tanggal_terima;
                    $result['asal'] = $detail->asal_surat;
                    $result['jenis'] = $detail->jenis_surat;
                } else {
                    $result['tujuan'] = $detail->tujuan_surat;
                    $result['penanggung_jawab'] = $detail->penanggung_jawab;
                }
                
                $results[] = $result;
            }
        }
        
        return $results;
    }

    /**
     * Get Binary Jaccard Results (untuk backward compatibility)
     */
    public static function getBinaryJaccardResults(array $queryTerms, ?string $letterType, ?string $start, ?string $end): array
    {
        if (empty($queryTerms)) {
            return [];
        }

        $suratIds = self::getFilteredSuratIds($letterType, $start, $end);
        
        if (empty($suratIds['masuk']) && empty($suratIds['keluar'])) {
            return [];
        }
        
        $results = [];
        $querySet = array_unique($queryTerms);
        
        // Proses surat masuk (binary)
        if (!empty($suratIds['masuk'])) {
            $terms = SuratTerm::where('surat_type', 'masuk')
                ->whereIn('surat_id', $suratIds['masuk'])
                ->get(['surat_id', 'term'])
                ->groupBy('surat_id');
                
            $details = SuratMasuk::whereIn('id', $suratIds['masuk'])
                ->get()
                ->keyBy('id');
                
            foreach ($terms as $id => $docTerms) {
                if (!isset($details[$id])) continue;
                
                $docTermsArray = $docTerms->pluck('term')->unique()->toArray();
                $jaccard = self::calculateBinaryJaccard($queryTerms, $docTermsArray);
                
                if ($jaccard > 0.1) {
                    $detail = $details[$id];
                    $results[] = [
                        'id'      => 'SM-' . $detail->id,
                        'nomor'   => $detail->nomor_surat,
                        'tanggal' => $detail->tanggal_surat,
                        'tanggal_terima' => $detail->tanggal_terima,
                        'asal'    => $detail->asal_surat,
                        'jenis'   => $detail->jenis_surat,
                        'isi'     => $detail->perihal,
                        'jaccard' => $jaccard,
                        'tipe'    => 'masuk',
                        'weighted' => false
                    ];
                }
            }
        }
        
        // Proses surat keluar (binary)
        if (!empty($suratIds['keluar'])) {
            $terms = SuratTerm::where('surat_type', 'keluar')
                ->whereIn('surat_id', $suratIds['keluar'])
                ->get(['surat_id', 'term'])
                ->groupBy('surat_id');
                
            $details = SuratKeluar::whereIn('id', $suratIds['keluar'])
                ->get()
                ->keyBy('id');
                
            foreach ($terms as $id => $docTerms) {
                if (!isset($details[$id])) continue;
                
                $docTermsArray = $docTerms->pluck('term')->unique()->toArray();
                $jaccard = self::calculateBinaryJaccard($queryTerms, $docTermsArray);
                
                if ($jaccard > 0.1) {
                    $detail = $details[$id];
                    $results[] = [
                        'id'      => 'SK-' . $detail->id,
                        'nomor'   => $detail->nomor_surat,
                        'tanggal' => $detail->tanggal_surat,
                        'tujuan'  => $detail->tujuan_surat,
                        'penanggung_jawab' => $detail->penanggung_jawab,
                        'isi'     => $detail->perihal,
                        'jaccard' => $jaccard,
                        'tipe'    => 'keluar',
                        'weighted' => false
                    ];
                }
            }
        }
        
        usort($results, fn($a, $b) => $b['jaccard'] <=> $a['jaccard']);
        return $results;
    }

    /**
     * Method wrapper untuk kompatibilitas
     */
    public static function getJaccardResults(array $queryTerms, ?string $letterType, ?string $start, ?string $end): array
    {
        // Pilih antara weighted atau binary berdasarkan konfigurasi
        $useWeighted = config('search.jaccard.weighted', true);
        
        if ($useWeighted) {
            // Untuk weighted, kita butuh vector, bukan hanya terms
            // Anda perlu memastikan controller mengirim $queryVec, bukan $queryTerms
            // Fallback: jika hanya ada terms, buat weights sederhana
            $queryVec = array_fill_keys($queryTerms, 1.0);
            return self::getWeightedJaccardResults($queryVec, $letterType, $start, $end);
        } else {
            return self::getBinaryJaccardResults($queryTerms, $letterType, $start, $end);
        }
    }

    private static function getFilteredSuratIds(?string $letterType, ?string $start, ?string $end): array
    {
        // Kode ini sudah benar
        $ids = ['masuk' => [], 'keluar' => []];
        
        if (!$letterType || $letterType == 'all' || $letterType == 'masuk') {
            $query = SuratMasuk::query();
            if ($start) $query->whereDate('tanggal_surat', '>=', $start);
            if ($end) $query->whereDate('tanggal_surat', '<=', $end);
            $ids['masuk'] = $query->pluck('id')->toArray();
        }
        
        if (!$letterType || $letterType == 'all' || $letterType == 'keluar') {
            $query = SuratKeluar::query();
            if ($start) $query->whereDate('tanggal_surat', '>=', $start);
            if ($end) $query->whereDate('tanggal_surat', '<=', $end);
            $ids['keluar'] = $query->pluck('id')->toArray();
        }
        
        return $ids;
    }
}
