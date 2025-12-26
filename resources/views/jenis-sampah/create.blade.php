@extends('layouts.app')
@section('title', 'Tambah Jenis Sampah')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 30px;">
            <i class="fas fa-plus"></i> Tambah Jenis Sampah
        </h1>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('jenis-sampah.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Jenis Sampah <span class="text-danger">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('jenis-sampah.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
