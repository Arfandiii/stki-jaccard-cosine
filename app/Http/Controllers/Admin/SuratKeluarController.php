<?php

namespace App\Http\Controllers\Admin;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuratTerm;
use Illuminate\Support\Facades\DB;
use App\Helpers\PreprocessingText;
use App\Helpers\TfidfService;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suratKeluar = SuratKeluar::latest()->paginate(20);
        return view('admin.surat-keluar.index', compact('suratKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.surat-keluar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'      => 'required',
            'tanggal_surat'    => 'required|date',
            'tujuan_surat'     => 'required',
            'perihal'          => 'required',
            'penanggung_jawab' => 'required',
            'file'             => 'nullable|file|mimes:pdf|max:2048',
        ]);

        DB::transaction(function () use ($request) {

            /* =======================
            * 1. Upload File
            * ======================= */
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('surat_keluar', 'public');
            }

            /* =======================
            * 2. Simpan Surat Keluar
            * ======================= */
            $surat = SuratKeluar::create([
                'nomor_surat'      => $request->nomor_surat,
                'tanggal_surat'    => $request->tanggal_surat,
                'tujuan_surat'     => $request->tujuan_surat,
                'perihal'          => $request->perihal,
                'penanggung_jawab' => $request->penanggung_jawab,
                'file_path'        => $filePath,
            ]);

            /* =======================
            * 3. Preprocessing Perihal
            * ======================= */
            $tokens = PreprocessingText::preprocessText($request->perihal);

            /* =======================
            * 4. Hitung TF
            * ======================= */
            $tf = array_count_values($tokens);

            /* =======================
            * 5. Simpan ke surat_terms
            * ======================= */
            foreach ($tf as $term => $count) {
                SuratTerm::create([
                    'surat_type' => 'keluar',
                    'surat_id'   => $surat->id,
                    'term'       => $term,
                    'tf'         => $count,
                    'tfidf'      => 0, // dihitung ulang global
                ]);
            }

            /* =======================
            * 6. Hitung TF-IDF Global
            * ======================= */
            TfidfService::calculate('keluar');
        });

        return redirect()
            ->route('admin.surat-keluar.index')
            ->with('success', 'Surat keluar berhasil ditambahkan & diproses');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('admin.surat-keluar.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('admin.surat-keluar.edit', compact('surat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        $request->validate([
            'nomor_surat'      => 'required|string|max:255',
            'tanggal_surat'    => 'required|date',
            'tujuan_surat'     => 'required|string|max:255',
            'perihal'          => 'required|string',
            'penanggung_jawab' => 'required|string|max:255',
            'file'             => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048'
        ]);

        DB::transaction(function () use ($request, $surat) {
            // 1. Handle file (hapus lama → simpan baru)
            $filePath = $surat->file_path;
            if ($request->hasFile('file')) {
                if ($surat->file_path && Storage::disk('public')->exists($surat->file_path)) {
                    Storage::disk('public')->delete($surat->file_path);
                }
                $filePath = $request->file('file')->store('surat_keluar', 'public');
            }

            // 2. Cek apakah perihal berubah
            $perihalChanged = $surat->perihal !== $request->perihal;

            // 3. Update surat
            $surat->update([
                'nomor_surat'      => $request->nomor_surat,
                'tanggal_surat'    => $request->tanggal_surat,
                'tujuan_surat'     => $request->tujuan_surat,
                'perihal'          => $request->perihal,
                'penanggung_jawab' => $request->penanggung_jawab,
                'file_path'        => $filePath
            ]);

            // 4. Re-preprocessing & TF-IDF hanya jika perihal berubah
            if ($perihalChanged) {
                SuratTerm::where('surat_type', 'keluar')
                        ->where('surat_id', $surat->id)
                        ->delete();

                $tokens = PreprocessingText::preprocessText($request->perihal);
                $tf = array_count_values($tokens);

                foreach ($tf as $term => $count) {
                    SuratTerm::create([
                        'surat_type' => 'keluar',
                        'surat_id'   => $surat->id,
                        'term'       => $term,
                        'tf'         => $count,
                        'tfidf'      => 0,
                    ]);
                }
                TfidfService::calculate('keluar');
            }
        });

        return redirect()->route('admin.surat-keluar.index')
                        ->with('success', 'Surat keluar berhasil diperbarui & diproses ulang');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        // 🔒 CEK DULU FILE-NYA ADA ATAU TIDAK
        if ($surat->file_path && Storage::exists($surat->file_path)) {
            Storage::delete($surat->file_path);
        }

        $surat->delete();

        return redirect()
            ->route('admin.surat-keluar.index')
            ->with('success', 'Surat berhasil dihapus');
    }
}
