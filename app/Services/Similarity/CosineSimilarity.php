<?php
namespace App\Services\Similarity;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\SuratTerm;

class CosineSimilarity
{
    
    public static function calculateCosineSimilarity($tfidfTable, $docCount)
    {
        $similarities = [];

        // Hitung panjang vektor Query
        $queryVectorLength = 0;
        foreach ($tfidfTable as $row) {
            $queryVectorLength += pow($row['Q'], 2);
        }
        $queryVectorLength = sqrt($queryVectorLength);

        // Identifikasi dokumen yang relevan (mengandung minimal 1 term dari query)
        $relevantDocs = [];

        foreach ($tfidfTable as $row) {
            if ($row['Q'] > 0) {
                for ($i = 1; $i <= $docCount; $i++) {
                    if (!empty($row["D$i"]) && $row["D$i"] > 0) {
                        $relevantDocs["D$i"] = true;
                    }
                }
            }
        }

        // Hitung cosine similarity HANYA untuk dokumen yang relevan
        foreach ($relevantDocs as $docKey => $_) {
            $i = intval(substr($docKey, 1)); // Ambil nomor dokumen dari "D1", "D2", ...

            $dotProduct = 0;
            $docVectorLength = 0;

            foreach ($tfidfTable as $row) {
                $docVal = $row["D$i"];
                $queryVal = $row["Q"];

                $dotProduct += $docVal * $queryVal;
                $docVectorLength += pow($docVal, 2);
            }

            $docVectorLength = sqrt($docVectorLength);

            // Hitung cosine similarity (hindari pembagian nol)
            $cosine = ($queryVectorLength > 0 && $docVectorLength > 0)
                ? round($dotProduct / ($queryVectorLength * $docVectorLength), 4)
                : 0;

            $similarities[] = [
                'doc' => "D$i",
                'similarity' => $cosine
            ];

        }
        usort($similarities, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        return $similarities;
    }

    /* ---------- LOGIC CARI & COSINE ---------- */
    public static function getCosineResults(array $queryVec, ?string $letterType, ?string $start, ?string $end): array
    {
        $terms = array_keys($queryVec);
        
        if (empty($terms)) {
            return [];
        }

        // Mulai query dengan WHERE IN terms
        $subQ = SuratTerm::whereIn('term', $terms);
        
        // Filter berdasarkan jenis surat
        if ($letterType && $letterType !== 'all') {
            $subQ->where('surat_type', $letterType);
        }

        // Filter tanggal dengan cara yang benar
        if ($start || $end) {
            $subQ->where(function ($q) use ($start, $end, $letterType) {
                // Surat Masuk
                if (!$letterType || $letterType === 'all' || $letterType === 'masuk') {
                    $q->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('surat_type', 'masuk');
                        
                        if ($start) {
                            $q2->whereHas('suratMasuk', function ($q3) use ($start) {
                                $q3->whereDate('tanggal_surat', '>=', $start);
                            });
                        }
                        
                        if ($end) {
                            $q2->whereHas('suratMasuk', function ($q3) use ($end) {
                                $q3->whereDate('tanggal_surat', '<=', $end);
                            });
                        }
                    });
                }
                
                // Surat Keluar
                if (!$letterType || $letterType === 'all' || $letterType === 'keluar') {
                    $q->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('surat_type', 'keluar');
                        
                        if ($start) {
                            $q2->whereHas('suratKeluar', function ($q3) use ($start) {
                                $q3->whereDate('tanggal_surat', '>=', $start);
                            });
                        }
                        
                        if ($end) {
                            $q2->whereHas('suratKeluar', function ($q3) use ($end) {
                                $q3->whereDate('tanggal_surat', '<=', $end);
                            });
                        }
                    });
                }
            });
        }

        // Ambil data dan group
        $rows = $subQ->get(['surat_type', 'surat_id', 'term', 'tfidf_norm'])
                    ->groupBy(['surat_type', 'surat_id']);

        $cosines = [];
        foreach ($rows as $type => $byId) {
            foreach ($byId as $id => $terms) {
                $dot = 0;
                foreach ($terms as $t) {
                    $dot += ($queryVec[$t->term] ?? 0) * $t->tfidf_norm;
                }
                if ($dot >= 0.1) {          // threshold lebih rendah untuk lebih banyak hasil
                    $cosines[] = [
                        'surat_type' => $type,
                        'surat_id'   => $id,
                        'cosine'     => round($dot, 4)
                    ];
                }
            }
        }
        
        // Urutkan berdasarkan cosine similarity
        usort($cosines, fn($a,$b) => $b['cosine'] <=> $a['cosine']);

        // Ambil detail surat
        $results = [];
        
        // Pisahkan ID surat masuk dan keluar
        $masukIds = [];
        $keluarIds = [];
        
        foreach ($cosines as $c) {
            if ($c['surat_type'] == 'masuk') {
                $masukIds[] = $c['surat_id'];
            } else {
                $keluarIds[] = $c['surat_id'];
            }
        }
        
        // Ambil data surat masuk
        $masuk = !empty($masukIds) 
            ? SuratMasuk::whereIn('id', $masukIds)->get()->keyBy('id')
            : collect();
        
        // Ambil data surat keluar
        $keluar = !empty($keluarIds)
            ? SuratKeluar::whereIn('id', $keluarIds)->get()->keyBy('id')
            : collect();

        // Format hasil
        foreach ($cosines as $c) {
            if ($c['surat_type'] == 'masuk' && isset($masuk[$c['surat_id']])) {
                $s = $masuk[$c['surat_id']];
                $results[] = [
                    'id'      => 'SM-'.$s->id,
                    'nomor'   => $s->nomor_surat,
                    'tanggal' => $s->tanggal_surat,
                    'tanggal_terima' => $s->tanggal_terima,
                    'asal'    => $s->asal_surat,
                    'jenis'   => $s->jenis_surat,
                    'isi'     => $s->perihal,
                    'cosine'  => $c['cosine'],
                    'tipe'    => 'masuk'
                ];
            } elseif ($c['surat_type'] == 'keluar' && isset($keluar[$c['surat_id']])) {
                $s = $keluar[$c['surat_id']];
                $results[] = [
                    'id'      => 'SK-'.$s->id,
                    'nomor'   => $s->nomor_surat,
                    'tanggal' => $s->tanggal_surat,
                    'tujuan'  => $s->tujuan_surat,
                    'penanggung_jawab' => $s->penanggung_jawab,
                    'isi'     => $s->perihal,
                    'cosine'  => $c['cosine'],
                    'tipe'    => 'keluar'
                ];
            }
        }
        
        return $results;
    }
}