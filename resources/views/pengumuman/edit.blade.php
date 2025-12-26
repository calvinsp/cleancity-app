@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 30px;">
            <i class="fas fa-edit"></i> Edit Pengumuman
        </h1>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pengumuman.update', $pengumuman) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" id="judul" name="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $pengumuman->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal" name="tanggal"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', $pengumuman->tanggal) }}" required>
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                        <textarea id="isi" name="isi"
                                  class="form-control @error('isi') is-invalid @enderror"
                                  rows="6" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
