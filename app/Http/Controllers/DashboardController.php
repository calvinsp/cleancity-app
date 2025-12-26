<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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
                'role'            => 'admin',
                'totalLaporan'    => $totalLaporan,
                'laporanPending'  => $laporanPending,
                'laporanDiproses' => $laporanDiproses,
                'laporanSelesai'  => $laporanSelesai,
                'totalUser'       => $totalUser,
                'recentReports'   => $recentReports,
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
                'role'           => 'petugas',
                'laporanPending' => $laporanPending,
                'recentReports'  => $recentReports,
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
                'role'         => 'user',
                'laporanSaya'  => $laporanSaya,
                'recentReports'=> $recentReports,
            ]);
        }

        // fallback kalau belum login
        return redirect()->route('login');
    }
}
