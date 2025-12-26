<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with('admin')
            ->latest('tanggal')
            ->paginate(10);

        return view('pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('pengumuman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'   => 'required|string|max:255',
            'isi'     => 'required|string|min:10',
            'tanggal' => 'required|date',
        ]);

        $validated['admin_id'] = Auth::id();

        Pengumuman::create($validated);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function show(Pengumuman $pengumuman)
    {
        return view('pengumuman.show', compact('pengumuman'));
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul'   => 'required|string|max:255',
            'isi'     => 'required|string|min:10',
            'tanggal' => 'required|date',
        ]);

        $pengumuman->update($validated);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
