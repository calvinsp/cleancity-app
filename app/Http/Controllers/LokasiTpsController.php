<?php

namespace App\Http\Controllers;

use App\Models\LokasiTps;
use Illuminate\Http\Request;

class LokasiTpsController extends Controller
{
    public function index()
    {
        $lokasi = LokasiTps::paginate(10);

        return view('lokasi.index', compact('lokasi'));
    }

    public function create()
    {
        return view('lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|unique:lokasi_tps,nama',
            'alamat'         => 'required|string',
            'kapasitas'      => 'required|integer|min:1',
            'jam_operasional'=> 'required|string',
        ]);

        LokasiTps::create($validated);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi TPS berhasil ditambahkan!');
    }

    public function show(LokasiTps $lokasi)
    {
        return view('lokasi.show', compact('lokasi'));
    }

    public function edit(LokasiTps $lokasi)
    {
        return view('lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, LokasiTps $lokasi)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|unique:lokasi_tps,nama,' . $lokasi->id,
            'alamat'         => 'required|string',
            'kapasitas'      => 'required|integer|min:1',
            'jam_operasional'=> 'required|string',
        ]);

        $lokasi->update($validated);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi TPS berhasil diperbarui!');
    }

    public function destroy(LokasiTps $lokasi)
    {
        $lokasi->delete();

        return redirect()->route('lokasi.index')->with('success', 'Lokasi TPS berhasil dihapus!');
    }
}
