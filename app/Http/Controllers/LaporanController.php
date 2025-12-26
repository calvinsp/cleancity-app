<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\JenisSampah;
use App\Models\LokasiTps;
use App\Models\User;
use App\Notifications\LaporanSelesaiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index($locale)
    {
        $user = Auth::user();

        if (
            $user &&
            (
                (method_exists($user, 'isAdmin') && $user->isAdmin()) ||
                (method_exists($user, 'isPetugas') && $user->isPetugas())
            )
        ) {
            $laporan = Laporan::with(['user', 'jenisSampah', 'lokasi'])->paginate(10);
        } elseif ($user) {
            $laporan = $user->laporan()->with(['jenisSampah', 'lokasi'])->paginate(10);
        } else {
            $laporan = Laporan::with(['user', 'jenisSampah', 'lokasi'])->paginate(10);
        }

        return view('laporan.index', compact('laporan'));
    }

    public function create($locale)
    {
        $jenisSampah = JenisSampah::all();
        $lokasi      = LokasiTps::all();

        return view('laporan.create', compact('jenisSampah', 'lokasi'));
    }

    public function store(Request $request, $locale)
    {
        $validated = $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'lokasi_id'       => 'required|exists:lokasi_tps,id',
            'deskripsi'       => 'required|string|min:10',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'pending';

        $laporan = Laporan::create($validated);

        return redirect()
            ->route('laporan.index', ['locale' => $locale])
            ->with('success', 'Laporan berhasil dibuat!');
    }

    // ======================
    //  DETAIL / EDIT / CRUD
    // ======================

    public function show($locale, $laporan)
    {
        $laporan = Laporan::with(['jenisSampah', 'lokasi', 'user'])->findOrFail($laporan);

        return view('laporan.show', compact('laporan'));
    }

    public function edit($locale, $laporan)
    {
        $laporan     = Laporan::findOrFail($laporan);
        $jenisSampah = JenisSampah::all();
        $lokasi      = LokasiTps::all();

        return view('laporan.edit', compact('laporan', 'jenisSampah', 'lokasi'));
    }

    public function update(Request $request, $locale, $laporan)
    {
        $laporan = Laporan::findOrFail($laporan);

        $validated = $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'lokasi_id'       => 'required|exists:lokasi_tps,id',
            'deskripsi'       => 'required|string|min:10',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        }

        $laporan->update($validated);

        return redirect()
            ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy(Request $request, $locale, $laporan)
    {
        $laporan = Laporan::findOrFail($laporan);

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()
            ->route('laporan.index', ['locale' => $locale])
            ->with('success', 'Laporan berhasil dihapus!');
    }

    // ======================
    //  UPDATE STATUS
    // ======================

    public function updateStatus(Request $request, $locale, $laporan)
    {
        $laporan = Laporan::findOrFail($laporan);
        $user    = Auth::user();

        $request->validate([
            'status' => 'required|string|in:pending,diproses,selesai,request_selesai',
        ]);

        // ADMIN
        if ($user->isAdmin()) {
            if (in_array($request->status, ['pending', 'diproses', 'selesai'])) {
                $laporan->status = $request->status;

                if ($request->status === 'selesai') {
                    $laporan->status_request = 'none';
                }

                $laporan->save();

                return redirect()
                    ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                    ->with('success', 'Status laporan berhasil diperbarui oleh admin.');
            }

            return redirect()
                ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                ->with('error', 'Status tidak valid untuk admin.');
        }

        // PETUGAS
        if ($user->isPetugas()) {
            if ($request->status === 'diproses' && $laporan->status === 'pending') {
                $laporan->status = 'diproses';
                $laporan->save();

                return redirect()
                    ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                    ->with('success', 'Status laporan diperbarui menjadi diproses.');
            }

            if ($request->status === 'request_selesai' && $laporan->status === 'diproses') {
                $laporan->status_request = 'request_selesai';
                $laporan->save();

                $admins = User::where('role_id', 1)->get();

                foreach ($admins as $admin) {
                    $admin->notify(new LaporanSelesaiNotification($laporan));
                }

                return redirect()
                    ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                    ->with('success', 'Permintaan penyelesaian laporan sudah dikirim ke admin.');
            }

            return redirect()
                ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                ->with('error', 'Petugas tidak diizinkan melakukan aksi ini.');
        }

        return redirect()
            ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
            ->with('error', 'Anda tidak memiliki hak untuk mengubah status laporan.');
    }
}
