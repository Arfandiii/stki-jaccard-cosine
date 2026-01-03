<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JaccardSimilarity;
use App\Helpers\CosineSimilarity;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

class SearchController extends Controller
{
    /* =======================
     * HALAMAN SEARCH
     * ======================= */
    public function index()
    {
        return view('admin.search.index');
    }

    /* =======================
     * PROSES SEARCH
     * ======================= */
    public function search(Request $request)
    {
        $request->validate([
            'query'       => 'required|string|min:2',
            'letter_type' => 'nullable|in:all,masuk,keluar',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $query      = $request->input('query');
        $type       = $request->letter_type ?? 'all';
        $startDate  = $request->start_date;
        $endDate    = $request->end_date;

        $startTime = microtime(true);

        $jaccardResults = [];
        $cosineResults  = [];

        /* =======================
         * SURAT MASUK
         * ======================= */
        if ($type === 'all' || $type === 'masuk') {

            foreach (JaccardSimilarity::calculate($query, 'masuk') as $row) {
                $doc = SuratMasuk::find($row['surat_id']);
                if (!$doc) continue;

                $jaccardResults[] = $this->mapResult($doc, $row['similarity'], 'masuk');
            }

            foreach (CosineSimilarity::calculate($query, 'masuk') as $row) {
                $doc = SuratMasuk::find($row['surat_id']);
                if (!$doc) continue;

                $cosineResults[] = $this->mapResult($doc, $row['similarity'], 'masuk');
            }
        }

        /* =======================
         * SURAT KELUAR
         * ======================= */
        if ($type === 'all' || $type === 'keluar') {

            foreach (JaccardSimilarity::calculate($query, 'keluar') as $row) {
                $doc = SuratKeluar::find($row['surat_id']);
                if (!$doc) continue;

                $jaccardResults[] = $this->mapResult($doc, $row['similarity'], 'keluar');
            }

            foreach (CosineSimilarity::calculate($query, 'keluar') as $row) {
                $doc = SuratKeluar::find($row['surat_id']);
                if (!$doc) continue;

                $cosineResults[] = $this->mapResult($doc, $row['similarity'], 'keluar');
            }
        }

        /* =======================
         * FILTER TANGGAL (AMAN)
         * ======================= */
        $filterByDate = function ($item) use ($startDate, $endDate) {
            if (empty($item['date'])) return true;
            if ($startDate && $item['date'] < $startDate) return false;
            if ($endDate && $item['date'] > $endDate) return false;
            return true;
        };

        $jaccardResults = array_values(array_filter($jaccardResults, $filterByDate));
        $cosineResults  = array_values(array_filter($cosineResults,  $filterByDate));

        /* =======================
         * SORTING DESC SCORE
         * ======================= */
        usort($jaccardResults, fn($a, $b) => $b['score'] <=> $a['score']);
        usort($cosineResults,  fn($a, $b) => $b['score'] <=> $a['score']);

        $executionTime = round(microtime(true) - $startTime, 4);

        return response()->json([
            'query'           => $query,
            'execution_time'  => $executionTime,
            'statistics'      => [
                'total_documents' => SuratMasuk::count() + SuratKeluar::count(),
                'surat_masuk'     => SuratMasuk::count(),
                'surat_keluar'    => SuratKeluar::count(),
            ],
            'jaccard_results' => $jaccardResults,
            'cosine_results'  => $cosineResults,
        ]);
    }

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
}
