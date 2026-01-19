<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Models\Query;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Total surat
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalSurat = $totalSuratMasuk + $totalSuratKeluar;
        
        // Statistik bulanan
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $suratMasukBulanIni = SuratMasuk::whereMonth('tanggal_surat', $bulanIni)
            ->whereYear('tanggal_surat', $tahunIni)
            ->count();
        
        $suratKeluarBulanIni = SuratKeluar::whereMonth('tanggal_surat', $bulanIni)
            ->whereYear('tanggal_surat', $tahunIni)
            ->count();
        
        // Statistik harian
        $hariIni = Carbon::today();
        $suratMasukHariIni = SuratMasuk::whereDate('created_at', $hariIni)->count();
        $suratKeluarHariIni = SuratKeluar::whereDate('created_at', $hariIni)->count();
        
        // Statistik Query
        $totalQueries = Query::count();
        $queriesToday = Query::whereDate('created_at', $hariIni)->count();
        
        // Query popular
        $popularQueries = Query::select('query_text', DB::raw('COUNT(*) as count'))
            ->groupBy('query_text')
            ->orderByDesc('count')
            ->take(5)
            ->get();
        
        // Chart data untuk 7 hari terakhir
        $chartData = $this->getChartData();
        
        // Recent queries
        $recentQueries = Query::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalSurat',
            'suratMasukBulanIni',
            'suratKeluarBulanIni',
            'suratMasukHariIni',
            'suratKeluarHariIni',
            'totalQueries',
            'queriesToday',
            'popularQueries',
            'chartData',
            'recentQueries'
        ));
    }

    /**
     * Get chart data for last 7 days
     */
    private function getChartData()
    {
        $days = [];
        $suratMasukData = [];
        $suratKeluarData = [];
        $queryData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->translatedFormat('D');
            $dateString = $date->toDateString();
            
            // Count surat masuk
            $masuk = SuratMasuk::whereDate('created_at', $date)->count();
            
            // Count surat keluar
            $keluar = SuratKeluar::whereDate('created_at', $date)->count();
            
            // Count queries
            $queries = Query::whereDate('created_at', $date)->count();
            
            $days[] = $dayName;
            $suratMasukData[] = $masuk;
            $suratKeluarData[] = $keluar;
            $queryData[] = $queries;
        }
        
        return [
            'days' => $days,
            'surat_masuk' => $suratMasukData,
            'surat_keluar' => $suratKeluarData,
            'queries' => $queryData
        ];
    }

    // ... (fungsi lainnya tetap sama seperti sebelumnya)
    public function profile()
    {
        return view('admin.profile');
    }

    public function history()
    {
        $queries = Query::latest()->paginate(10);
        return view('admin.history', compact('queries'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('admin.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('admin.profile')
                        ->with('success', 'Profil atau password berhasil diperbarui.');
    }
}