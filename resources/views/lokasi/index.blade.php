@extends('layouts.app')
@section('title', 'Lokasi TPS')
@section('content')

<h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 30px;">
    <i class="fas fa-map-marker-alt"></i> Daftar Lokasi TPS
</h1>

@if(auth()->user()->isAdmin())
<div class="mb-4">
    <a href="{{ route('lokasi.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Lokasi
    </a>
</div>
@endif

<div class="card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kapasitas</th>
                <th>Jam Operasional</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lokasi as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->nama }}</strong></td>
                <td>{{ $item->alamat }}</td>
                <td>{{ $item->kapasitas }}</td>
                <td>{{ $item->jam_operasional }}</td>
                <td>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('lokasi.edit', $item) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('lokasi.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin hapus lokasi ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $lokasi->links('pagination::bootstrap-4') }}
</div>

@endsection
