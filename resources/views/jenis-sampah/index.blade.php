@extends('layouts.app')
@section('title', 'Jenis Sampah')
@section('content')

<h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 30px;">
    <i class="fas fa-list"></i> Daftar Jenis Sampah
</h1>

@if(auth()->user()->isAdmin())
<div class="mb-4">
    <a href="{{ route('jenis-sampah.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Jenis Sampah
    </a>
</div>
@endif

<div class="card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jenisSampah as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->nama }}</strong></td>
                <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                <td>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('jenis-sampah.edit', $item) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('jenis-sampah.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $jenisSampah->links('pagination::bootstrap-4') }}

@endsection
