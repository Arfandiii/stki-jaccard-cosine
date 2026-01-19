<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Http\Controllers\Controller;
use App\Models\JenisSuratMasuk;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratTerm;
use Illuminate\Support\Facades\DB;
use App\Services\TextPreprocessor;
use App\Services\TfidfService;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suratMasuk = SuratMasuk::latest()->paginate(20);
        return view('admin.surat-masuk.index', compact('suratMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisSurat = JenisSuratMasuk::all(); // ambil semua jenis surat

        return view('admin.surat-masuk.create', compact('jenisSurat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Menyimpan data + upload PDF + preprocessing otomatis
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'asal_surat' => 'required',
            'perihal' => 'required',
            'jenis_surat' => 'required|exists:jenis_surat_masuk,id',
            'file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048'
        ]);

        DB::transaction(function () use ($request) {

            // 1. Upload file
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('surat_masuk', 'public');
            }

            // 2. Simpan surat masuk
            $surat = SuratMasuk::create([
                'nomor_surat' => $request->nomor_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'asal_surat' => $request->asal_surat,
                'perihal' => $request->perihal,
                'jenis_surat_id' => $request->jenis_surat,
                'file_path' => $filePath,
            ]);

            // 3. Ambil teks perihal
            $this->saveTerms('masuk', $surat->id, $request->perihal);
            
        });
        
        // 5. TF-IDF GLOBAL (di luar transaksi)
        TfidfService::recalculateGlobalTFIDF();

        return redirect()->route('admin.surat-masuk.index')
            ->with('success', 'Surat berhasil ditambahkan & diproses');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('admin.surat-masuk.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $jenisSurat = JenisSuratMasuk::all(); // ambil semua jenis surat
        return view('admin.surat-masuk.edit', compact('surat', 'jenisSurat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        $request->validate([
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'asal_surat' => 'required',
            'perihal' => 'required',
            'jenis_surat' => 'required|exists:jenis_surat_masuk,id',
            'file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048'
        ]);

        DB::transaction(function () use ($request, $surat) {

            // Upload file (jika ada)
            $filePath = $surat->file_path;
            if ($request->hasFile('file')) {
                if ($surat->file_path && Storage::disk('public')->exists($surat->file_path)) {
                    Storage::disk('public')->delete($surat->file_path);
                }
                $filePath = $request->file('file')->store('surat_masuk', 'public');
            }

            // Cek apakah perihal berubah
            $perihalChanged = $surat->perihal !== $request->perihal;

            // Update surat
            $surat->update([
                'nomor_surat' => $request->nomor_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'asal_surat' => $request->asal_surat,
                'perihal' => $request->perihal,
                'jenis_surat_id' => $request->jenis_surat,
                'file_path' => $filePath
            ]);

            // 🔁 Preprocessing ulang HANYA jika perihal berubah
            if ($perihalChanged) {

                // Hapus term lama
                SuratTerm::where('surat_type', 'masuk')
                    ->where('surat_id', $surat->id)
                    ->delete();

                // Preprocessing
                $this->saveTerms('masuk', $surat->id, $request->perihal);

            }
            // Setelah transaksi, TF-IDF GLOBAL (di luar transaksi)
            TfidfService::recalculateGlobalTFIDF();
        });

        return redirect()->route('admin.surat-masuk.index')
            ->with('success', 'Surat berhasil diperbarui & diproses ulang');
    }

    private function saveTerms(string $type, int $suratId, string $text): void
    {
        $tokens = TextPreprocessor::preprocessText($text);
        $tf = array_count_values($tokens);

        foreach ($tf as $term => $count) {
            SuratTerm::create([
                'surat_type' => $type,
                'surat_id'   => $suratId,
                'term'       => $term,
                'tf'         => $count,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // 🔒 CEK DULU FILE-NYA ADA ATAU TIDAK
        if ($surat->file_path && Storage::exists($surat->file_path)) {
            Storage::delete($surat->file_path);
        }

        $surat->delete();

        // ➕ Recalculate IDF & TF-IDF global
        TfidfService::recalculateGlobalTFIDF();

        return redirect()
            ->route('admin.surat-masuk.index')
            ->with('success', 'Surat berhasil dihapus');
    }

}
