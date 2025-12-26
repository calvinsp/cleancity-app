@extends('layouts.app')
@section('title', 'Edit Lokasi TPS')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 30px;">
            <i class="fas fa-edit"></i> Edit Lokasi TPS
        </h1>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('lokasi.update', $lokasi) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" id="nama" name="nama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $lokasi->nama) }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea id="alamat" name="alamat"
                                  class="form-control @error('alamat') is-invalid @enderror"
                                  rows="3" required>{{ old('alamat', $lokasi->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="kapasitas" class="form-label">Kapasitas (m³) <span class="text-danger">*</span></label>
                        <input type="number" id="kapasitas" name="kapasitas"
                               class="form-control @error('kapasitas') is-invalid @enderror"
                               value="{{ old('kapasitas', $lokasi->kapasitas) }}" required min="1">
                        @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="jam_operasional" class="form-label">Jam Operasional <span class="text-danger">*</span></label>
                        <input type="text" id="jam_operasional" name="jam_operasional"
                               class="form-control @error('jam_operasional') is-invalid @enderror"
                               value="{{ old('jam_operasional', $lokasi->jam_operasional) }}" required>
                        @error('jam_operasional')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
