<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SearchController extends Controller
{
    /* ---------- HALAMAN UTAMA ---------- */
        public function index()
    {
        return view('admin.search.index1');
    }

    public function search(Request $request)
    {
        $query      = $request->input('query');
        $letterType = $request->input('letterType');
        $startDate  = $request->input('startDate');
        $endDate    = $request->input('endDate');
        $method     = $request->input('method', 'both'); // tambahkan select di form jika perlu


        $preprocessor = DebugController::preprocessQuery(
            $query,
            $letterType,
            $startDate,
            $endDate,
            $method
        );
        
        dd($preprocessor);

        return view('admin.search.index1');
    }
    
}