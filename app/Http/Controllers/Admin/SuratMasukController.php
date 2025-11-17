<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Http\Controllers\Controller;
use App\Models\JenisSuratMasuk;
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
            'file_path' => 'nullable|file|mimes:pdf,jpg,png,doc,docx'
        ]);

        SuratMasuk::create([
            'nomor_surat' => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_terima' => $request->tanggal_terima,
            'asal_surat' => $request->asal_surat,
            'perihal' => $request->perihal,
            'jenis_surat_id' => $request->jenis_surat, // pakai ID
            'file_path' => $request->file('file_path')
                ? $request->file('file_path')->store('surat_masuk')
                : null,
        ]);

        return redirect()->route('admin.surat-masuk.index')->with('success', 'Surat berhasil ditambahkan');
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
