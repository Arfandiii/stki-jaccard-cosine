<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Query;

class HistoryController extends Controller
{
    public function index()
    {
        $histories = Query::latest()->paginate(10);
        return view('admin.history', compact('histories'));
    }
    
    /* Hapus 1 record */
    public function destroy(Query $history)
    {
        $history->delete();

        return back()->with('success', 'History berhasil dihapus.');
    }

    /* Hapus semua record milik user yang sedang login */
    public function destroyAll()
    {
        Query::query()->delete();
        return back()->with('success', 'Semua history berhasil dihapus.');
    }
}