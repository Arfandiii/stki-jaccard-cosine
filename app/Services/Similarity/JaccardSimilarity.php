<?php

namespace App\Services\Similarity;

use App\Models\SuratTerm;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;

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

    public static function getJaccardResults(array $queryTerms, ?string $letterType, ?string $start, ?string $end): array
    {
        if (empty($queryTerms)) {
            return [];
        }

        // Ambil surat ID yang memenuhi filter
        $suratIds = self::getFilteredSuratIds($letterType, $start, $end);
        
        if (empty($suratIds['masuk']) && empty($suratIds['keluar'])) {
            return [];
        }
        
        $results = [];
        $querySet = array_unique($queryTerms);
        
        // Proses surat masuk
        if (!empty($suratIds['masuk'])) {
            // Ambil semua terms untuk surat masuk sekaligus
            $masukTerms = SuratTerm::where('surat_type', 'masuk')
                ->whereIn('surat_id', $suratIds['masuk'])
                ->get(['surat_id', 'term'])
                ->groupBy('surat_id');
                
            // Ambil detail surat masuk sekaligus
            $masukDetails = SuratMasuk::whereIn('id', $suratIds['masuk'])
                ->get()
                ->keyBy('id');
                
            foreach ($masukTerms as $id => $terms) {
                if (!isset($masukDetails[$id])) continue;
                
                $docTerms = $terms->pluck('term')->unique()->toArray();
                $intersection = count(array_intersect($querySet, $docTerms));
                $union = count(array_unique(array_merge($querySet, $docTerms)));
                
                if ($union > 0) {
                    $jaccard = $intersection / $union;
                    
                    if ($jaccard > 0.1) {
                        $detail = $masukDetails[$id];
                        $results[] = [
                            'id'      => 'SM-' . $detail->id,
                            'nomor'   => $detail->nomor_surat,
                            'tanggal' => $detail->tanggal_surat,
                            'tanggal_terima' => $detail->tanggal_terima,
                            'asal'    => $detail->asal_surat,
                            'jenis'   => $detail->jenis_surat,
                            'isi'     => $detail->perihal,
                            'jaccard' => round($jaccard, 4),
                            'tipe'    => 'masuk'
                        ];
                    }
                }
            }
        }
        
        // Proses surat keluar (sama seperti di atas)
        if (!empty($suratIds['keluar'])) {
            $keluarTerms = SuratTerm::where('surat_type', 'keluar')
                ->whereIn('surat_id', $suratIds['keluar'])
                ->get(['surat_id', 'term'])
                ->groupBy('surat_id');
                
            $keluarDetails = SuratKeluar::whereIn('id', $suratIds['keluar'])
                ->get()
                ->keyBy('id');
                
            foreach ($keluarTerms as $id => $terms) {
                if (!isset($keluarDetails[$id])) continue;
                
                $docTerms = $terms->pluck('term')->unique()->toArray();
                $intersection = count(array_intersect($querySet, $docTerms));
                $union = count(array_unique(array_merge($querySet, $docTerms)));
                
                if ($union > 0) {
                    $jaccard = $intersection / $union;
                    
                    if ($jaccard > 0.1) {
                        $detail = $keluarDetails[$id];
                        $results[] = [
                            'id'      => 'SK-' . $detail->id,
                            'nomor'   => $detail->nomor_surat,
                            'tanggal' => $detail->tanggal_surat,
                            'tujuan'  => $detail->tujuan_surat,
                            'penanggung_jawab' => $detail->penanggung_jawab,
                            'isi'     => $detail->perihal,
                            'jaccard' => round($jaccard, 4),
                            'tipe'    => 'keluar'
                        ];
                    }
                }
            }
        }
        
        // Urutkan berdasarkan score tertinggi
        usort($results, fn($a, $b) => $b['jaccard'] <=> $a['jaccard']);
        
        return $results;
    }

    private static function getFilteredSuratIds(?string $letterType, ?string $start, ?string $end): array
    {
        $ids = ['masuk' => [], 'keluar' => []];
        
        if (!$letterType || $letterType == 'all' || $letterType == 'masuk') {
            $query = SuratMasuk::query();
            
            if ($start) {
                $query->whereDate('tanggal_surat', '>=', $start);
            }
            if ($end) {
                $query->whereDate('tanggal_surat', '<=', $end);
            }
            
            $ids['masuk'] = $query->pluck('id')->toArray();
        }
        
        if (!$letterType || $letterType == 'all' || $letterType == 'keluar') {
            $query = SuratKeluar::query();
            
            if ($start) {
                $query->whereDate('tanggal_surat', '>=', $start);
            }
            if ($end) {
                $query->whereDate('tanggal_surat', '<=', $end);
            }
            
            $ids['keluar'] = $query->pluck('id')->toArray();
        }
        
        return $ids;
    }
}