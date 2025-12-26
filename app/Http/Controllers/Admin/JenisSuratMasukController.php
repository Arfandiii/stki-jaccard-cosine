<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisSuratMasuk;

class JenisSuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisSurat = JenisSuratMasuk::latest()->paginate(10);
        return view('admin.jenis-surat.index', compact('jenisSurat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_surat_masuk,nama_jenis',
        ], [
            'nama_jenis.required' => 'Nama jenis surat wajib diisi.',
            'nama_jenis.unique'   => 'Jenis surat ini sudah ada.',
        ]);

        JenisSuratMasuk::create($request->only('nama_jenis'));

        return redirect()->route('admin.jenis-surat.index')
                        ->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function update(Request $request, JenisSuratMasuk $jenisSurat)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_surat_masuk,nama_jenis,'.$jenisSurat->id,
        ], [
            'nama_jenis.required' => 'Nama jenis surat wajib diisi.',
            'nama_jenis.unique'   => 'Jenis surat ini sudah ada.',
        ]);

        $jenisSurat->update($request->only('nama_jenis'));

        return redirect()->route('admin.jenis-surat.index')
                        ->with('success', 'Jenis surat berhasil diperbarui.');
    }

    /*----------  DELETE (modal konfirmasi)  ----------*/
    public function destroy(JenisSuratMasuk $jenisSurat)
    {
        if ($jenisSurat->suratMasuk()->exists()) {
            return redirect()->route('admin.jenis-surat.index')
                                ->with('error', 'Jenis surat ini sedang digunakan, tidak dapat dihapus.');
        }

        $jenisSurat->delete();

        return redirect()->route('admin.jenis-surat.index')
                            ->with('success', 'Jenis surat berhasil dihapus.');
    }

    /**
     * Ambil satu data untuk diedit (ajax)
     */
    public function get(JenisSuratMasuk $jenisSurat)
    {
        return response()->json($jenisSurat);
    }
}
