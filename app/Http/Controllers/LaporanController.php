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
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index($locale)
    {
        $user = Auth::user();
        $from = request('from');
        $to   = request('to');

        $query = Laporan::with(['user', 'jenisSampah', 'lokasi']);

        // Filter by date range
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Order by latest first
        $query->orderByDesc('created_at');

        // Filter by role
        if (
            $user &&
            (
                (method_exists($user, 'isAdmin') && $user->isAdmin()) ||
                (method_exists($user, 'isPetugas') && $user->isPetugas())
            )
        ) {
            $laporan = $query->paginate(10);
        } elseif ($user) {
            $laporan = $user->laporan()
                ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
                ->with(['jenisSampah', 'lokasi'])
                ->orderByDesc('created_at')
                ->paginate(10);
        } else {
            $laporan = $query->paginate(10);
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
            ->with('success', __('Laporan berhasil dibuat!'));
    }

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

        // Handle hapus foto lama
        if ($request->has('hapus_foto') && $laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
            $laporan->foto = null;
            $laporan->save();
        }

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        }

        $laporan->update($validated);

        return redirect()
            ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
            ->with('success', __('Laporan berhasil diperbarui!'));
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
            ->with('success', __('Laporan berhasil dihapus!'));
    }

    public function export(Request $request): StreamedResponse
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $query = Laporan::with(['user', 'jenisSampah', 'lokasi'])
            ->orderByDesc('created_at');

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $laporan = $query->get();

        $filename = 'laporan-cleancity-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($laporan) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                __('ID'),
                __('Pelapor'),
                __('Email'),
                __('Jenis Sampah'),
                __('Lokasi'),
                __('Status'),
                __('Tanggal Laporan'),
                __('Deskripsi'),
            ]);

            foreach ($laporan as $item) {
                fputcsv($handle, [
                    $item->id,
                    optional($item->user)->name ?? '-',
                    optional($item->user)->email ?? '-',
                    optional($item->jenisSampah)->getTranslatedName() ?? '-',
                    optional($item->lokasi)->getTranslatedName() ?? '-',
                    $item->getStatusLabel() ?? __('Unknown'),
                    optional($item->created_at)->format('Y-m-d H:i') ?? '-',
                    preg_replace("/\r\n|\r|\n/", ' ', (string) $item->deskripsi),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

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
                    ->with('success', __('Status laporan berhasil diperbarui oleh admin.'));
            }

            return redirect()
                ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                ->with('error', __('Status tidak valid untuk admin.'));
        }

        // PETUGAS
        if ($user->isPetugas()) {
            if ($request->status === 'diproses' && $laporan->status === 'pending') {
                $laporan->status = 'diproses';
                $laporan->save();

                return redirect()
                    ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                    ->with('success', __('Status laporan diperbarui menjadi diproses.'));
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
                    ->with('success', __('Permintaan penyelesaian laporan sudah dikirim ke admin.'));
            }

            return redirect()
                ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
                ->with('error', __('Petugas tidak diizinkan melakukan aksi ini.'));
        }

        return redirect()
            ->route('laporan.show', ['locale' => $locale, 'laporan' => $laporan->id])
            ->with('error', __('Anda tidak memiliki hak untuk mengubah status laporan.'));
    }
}
