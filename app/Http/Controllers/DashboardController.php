<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data agregasi 30 hari terakhir (dipakai admin & petugas saja)
        $last30Days = Carbon::now()->subDays(30);

        $reportsByJenisLast30 = Laporan::selectRaw('jenis_sampah_id, COUNT(*) as total')
            ->where('created_at', '>=', $last30Days)
            ->groupBy('jenis_sampah_id')
            ->with('jenisSampah')
            ->get();

        // ADMIN
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            $totalLaporan    = Laporan::count();
            $laporanPending  = Laporan::where('status', 'pending')->count();
            $laporanDiproses = Laporan::where('status', 'diproses')->count();
            $laporanSelesai  = Laporan::where('status', 'selesai')->count();
            $totalUser       = User::where('role_id', 3)->count();

            $recentReports = Laporan::with(['user', 'jenisSampah', 'lokasi'])
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard', [
                'role'                   => 'admin',
                'totalLaporan'           => $totalLaporan,
                'laporanPending'         => $laporanPending,
                'laporanDiproses'        => $laporanDiproses,
                'laporanSelesai'         => $laporanSelesai,
                'totalUser'              => $totalUser,
                'recentReports'          => $recentReports,
                'reportsByJenisLast30'   => $reportsByJenisLast30,
            ]);
        }

        // PETUGAS
        if ($user && method_exists($user, 'isPetugas') && $user->isPetugas()) {
            $laporanPending = Laporan::where('status', 'pending')->count();

            $recentReports = Laporan::with(['user', 'jenisSampah', 'lokasi'])
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard', [
                'role'                 => 'petugas',
                'laporanPending'       => $laporanPending,
                'recentReports'        => $recentReports,
                'reportsByJenisLast30' => $reportsByJenisLast30,
            ]);
        }

        // USER BIASA (PELAPOR)
        if ($user) {
            $laporanSaya = $user->laporan()->count();

            $recentReports = $user->laporan()
                ->with(['jenisSampah', 'lokasi'])
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard', [
                'role'                 => 'user',
                'laporanSaya'          => $laporanSaya,
                'recentReports'        => $recentReports,
                // user biasa tidak terlalu butuh agregasi global, tapi kalau mau bisa ditampilkan juga:
                'reportsByJenisLast30' => null,
            ]);
        }

        // fallback kalau belum login
        return redirect()->route('login');
    }
}
