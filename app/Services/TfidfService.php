<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class TfidfService
{
    
    /**
     * Hitung frekuensi term unik dari dokumen dan query.
     * 
     * @param array $documents Dokumen yang akan dihitung frekuensi termnya.
     * @param array $queryTokens Query yang akan dihitung frekuensi termnya.
     * 
     * @return array Table yang berisi frekuensi term untuk tiap dokumen dan query.
     */
    public static function calculateTermFrequencies($documents, $queryTokens)
    {
        $allTerms = [];

        // Kumpulkan semua term unik dari dokumen
        foreach ($documents as $doc) {
            foreach ($doc['tokens'] as $term) {
                $allTerms[] = $term;
            }
        }

        // Tambahkan juga dari query
        foreach ($queryTokens as $term) {
            $allTerms[] = $term;
        }

        $uniqueTerms = array_values(array_unique($allTerms));

        $tfTable = [];

        foreach ($uniqueTerms as $term) {
            $row = ['term' => $term];

            // Hitung TF untuk tiap dokumen
            foreach ($documents as $index => $doc) {
                $count = array_count_values($doc['tokens'])[$term] ?? 0;
                $row["D" . ($index + 1)] = $count;
            }

            // Hitung TF untuk query
            $row["Q"] = array_count_values($queryTokens)[$term] ?? 0;

            $tfTable[] = $row;
        }

        return $tfTable;
    }

    public static function calculateTFWeight($tfTable, $docCount)
    {
        $tfWeightTable = [];

        foreach ($tfTable as $row) {
            $newRow = ['term' => $row['term']];

            // Proses setiap dokumen
            for ($i = 1; $i <= $docCount; $i++) {
                $tf = $row["D$i"];
                $newRow["D$i"] = $tf > 0 ? round(1 + log10($tf), 4) : 0;
            }

            // Proses Query
            $tfQ = $row['Q'];
            $newRow['Q'] = $tfQ > 0 ? round(1 + log10($tfQ), 4) : 0;

            $tfWeightTable[] = $newRow;
        }

        return $tfWeightTable;
    }

    public static function calculateIDF($tfTable, $docCount)
    {
        $idfTable = [];

        foreach ($tfTable as $row) {
            $term = $row['term'];

            // Hitung DF (jumlah dokumen yang memiliki term ini)
            $df = 0;
            for ($i = 1; $i <= $docCount; $i++) {
                if (!empty($row["D$i"]) && $row["D$i"] > 0) {
                    $df++;
                }
            }

            // Hitung IDF (hindari pembagian nol)
            $idf = $df > 0 ? round(log10($docCount / $df), 4) : 0;
            // $idf = log10(($docCount + 1) / ($df + 1)) + 1;

            $idfTable[] = [
                'term' => $term,
                'df' => $df,
                'idf' => $idf
            ];
        }

        return $idfTable;
    }

    public static function calculateTFIDF($tfWeightTable, $idfTable, $docCount)
    {
        $tfidfTable = [];
        $idfMap = array_column($idfTable, 'idf', 'term');

        foreach ($tfWeightTable as $tfRow) {
            $term = $tfRow['term'];
            $idf = $idfMap[$term] ?? 0;

            $row = ['term' => $term];

            // Kalikan TF Weight * IDF untuk tiap dokumen
            for ($i = 1; $i <= $docCount; $i++) {
                $tfWeight = $tfRow["D$i"];
                $row["D$i"] = round($tfWeight * $idf, 4);
            }

            // Kalikan untuk Query
            $tfWeightQ = $tfRow["Q"];
            $row["Q"] = round($tfWeightQ * $idf, 4);

            $tfidfTable[] = $row;
        }

        return $tfidfTable;
    }

    public static function normalizeTFIDF($tfidfTable, $docCount)
    {
        $norms = [];

        // Hitung panjang vektor tiap dokumen + query
        for ($i = 1; $i <= $docCount; $i++) {
            $norms["D$i"] = 0;
        }
        $norms["Q"] = 0;

        foreach ($tfidfTable as $row) {
            for ($i = 1; $i <= $docCount; $i++) {
                $norms["D$i"] += pow($row["D$i"], 2);
            }
            $norms["Q"] += pow($row["Q"], 2);
        }

        // Akar kuadrat
        foreach ($norms as $k => $v) {
            $norms[$k] = sqrt($v);
        }

        // Normalisasi
        $normalizedTable = [];

        foreach ($tfidfTable as $row) {
            $newRow = ['term' => $row['term']];

            for ($i = 1; $i <= $docCount; $i++) {
                $denom = $norms["D$i"];
                $newRow["D$i"] = $denom > 0
                    ? round($row["D$i"] / $denom, 6)
                    : 0;
            }

            $denomQ = $norms["Q"];
            $newRow["Q"] = $denomQ > 0
                ? round($row["Q"] / $denomQ, 6)
                : 0;

            $normalizedTable[] = $newRow;
        }

        return [
            'normalized' => $normalizedTable,
            'norms' => $norms // optional: buat debug / laporan
        ];
    }

    public static function recalculateGlobalTFIDF()
    {
        // Ambil semua dokumen
        $documents = DB::table('surat_terms')
            ->select('surat_type', 'surat_id', 'term', 'tf')
            ->get();

        // Total dokumen unik
        $docIds = $documents
            ->map(fn($d) => $d->surat_type . '-' . $d->surat_id)
            ->unique();

        $N = $docIds->count();

        if ($N === 0) return;

        /* =====================
        * 1. HITUNG DF & IDF
        * ===================== */
        $dfMap = [];

        foreach ($documents as $row) {
            $key = $row->term;
            $docKey = $row->surat_type . '-' . $row->surat_id;
            $dfMap[$key][$docKey] = true;
        }

        $idfMap = [];
        foreach ($dfMap as $term => $docs) {
            $df = count($docs);
            $idfMap[$term] = $df > 0
                ? log10($N / $df)
                : 0;
        }

        /* =====================
        * 2. HITUNG TF-IDF
        * ===================== */
        $tfidfByDoc = [];

        foreach ($documents as $row) {
            $docKey = $row->surat_type . '-' . $row->surat_id;
            $idf    = $idfMap[$row->term] ?? 0;        // <- sudah ada
            $tfidf  = $row->tf * $idf;
            $tfidfByDoc[$docKey][] = $tfidf;

            DB::table('surat_terms')
                ->where('surat_type', $row->surat_type)
                ->where('surat_id', $row->surat_id)
                ->where('term', $row->term)
                ->update([
                    'idf' => $idf,
                    'tfidf' => $tfidf
                ]);
        }

        /* =====================
        * 3. NORMALISASI
        * ===================== */
        foreach ($tfidfByDoc as $docKey => $values) {
            $norm = sqrt(array_sum(array_map(fn($v) => $v * $v, $values)));
            if ($norm == 0) continue;
            [$type, $id] = explode('-', $docKey);
            DB::table('surat_terms')
                ->where('surat_type', $type)
                ->where('surat_id', $id)
                ->update([
                    'tfidf_norm' => DB::raw("tfidf / $norm")
                ]);
        }
    }

}