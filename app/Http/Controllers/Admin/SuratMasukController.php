<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        return view('admin.surat-masuk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // Menyimpan data + upload PDF + preprocessing otomatis
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'   => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima'=> 'required|date|after_or_equal:tanggal_surat',
            'asal_surat'    => 'required|string|max:255',
            'perihal'       => 'required|string',
            'jenis_surat'   => 'required|string|max:100',
            'file'          => 'nullable|mimes:pdf|max:2048', // 2 MB
        ]);

        // upload PDF (jika ada)
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('scans/surat-masuk');
        }

        // simpan ke DB (otomatis memicu event booted → generateTerms)
        SuratMasuk::create([
            'nomor_surat'   => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_terima'=> $request->tanggal_terima,
            'asal_surat'    => $request->asal_surat,
            'perihal'       => $request->perihal,
            'jenis_surat'   => $request->jenis_surat,
            'file_path'     => $path,
        ]);

        return redirect()->route('surat-masuk.index')
                        ->with('success', 'Surat masuk berhasil disimpan & diindeks.');
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
        return view('admin.surat-masuk.edit', compact('surat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
