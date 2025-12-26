<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use Illuminate\Http\Request;

class JenisSampahController extends Controller
{
    public function index()
    {
        $jenisSampah = JenisSampah::paginate(10);

        return view('jenis-sampah.index', compact('jenisSampah'));
    }

    public function create()
    {
        return view('jenis-sampah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|unique:jenis_sampah,nama',
            'deskripsi' => 'nullable|string',
        ]);

        JenisSampah::create($validated);

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil ditambahkan!');
    }

    public function show(JenisSampah $jenisSampah)
    {
        return view('jenis-sampah.show', compact('jenisSampah'));
    }

    public function edit(JenisSampah $jenisSampah)
    {
        return view('jenis-sampah.edit', compact('jenisSampah'));
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|unique:jenis_sampah,nama,' . $jenisSampah->id,
            'deskripsi' => 'nullable|string',
        ]);

        $jenisSampah->update($validated);

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil diperbarui!');
    }

    public function destroy(JenisSampah $jenisSampah)
    {
        $jenisSampah->delete();

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil dihapus!');
    }
}
