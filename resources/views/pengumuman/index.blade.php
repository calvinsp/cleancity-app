@extends('layouts.app')
@section('title', 'Pengumuman')
@section('content')

<h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 30px;">
    <i class="fas fa-bullhorn"></i> Daftar Pengumuman
</h1>

@if(auth()->user()->isAdmin())
<div class="mb-4">
    <a href="{{ route('pengumuman.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Pengumuman
    </a>
</div>
@endif

<div class="card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Admin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengumuman as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->judul }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->admin->name ?? '-' }}</td>
                <td>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('pengumuman.edit', $item) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('pengumuman.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin hapus pengumuman ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">Tidak ada pengumuman</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $pengumuman->links('pagination::bootstrap-4') }}
</div>

@endsection
