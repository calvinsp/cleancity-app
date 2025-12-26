@extends('layouts.app')

@section('title', __('Detail Laporan'))

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        {{ __('Detail Laporan') }}
    </h2>
@endsection

@section('content')
@php
    $locale = request()->route('locale') ?? app()->getLocale();
@endphp

<div class="max-w-5xl mx-auto">
    {{-- HEADER DETAIL --}}
    <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 p-6 mb-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h5 mb-0">
                {{ __('Detail Laporan') }} :
                <span class="fw-semibold">{{ $laporan->getStatusLabel() }}</span>
            </h1>
        </div>

        {{-- INFO UTAMA --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="mb-1"><strong>{{ __('Pembuat Laporan') }}:</strong></p>
                <p class="mb-3">{{ $laporan->user->name }} ({{ $laporan->user->role->role_name }})</p>

                <p class="mb-1"><strong>{{ __('Jenis Sampah') }}:</strong></p>
                <p class="mb-0">{{ $laporan->jenisSampah->nama }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>{{ __('Tanggal Laporan') }}:</strong></p>
                <p class="mb-3">{{ $laporan->created_at->format('d/m/Y H:i') }}</p>

                <p class="mb-1"><strong>{{ __('Lokasi TPS') }}:</strong></p>
                <p class="mb-0">{{ $laporan->lokasi->nama }}<br>{{ $laporan->lokasi->alamat }}</p>
            </div>
        </div>

        <div class="mb-3">
            <p class="mb-1"><strong>{{ __('Deskripsi') }}:</strong></p>
            <p class="mb-0">{{ $laporan->deskripsi }}</p>
        </div>

        @if($laporan->foto)
            <div class="mt-3">
                <p class="mb-2"><strong>{{ __('Foto') }}:</strong></p>
                <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Laporan"
                     class="img-fluid rounded border" style="max-height: 400px;">
            </div>
        @endif

        @if($laporan->status_request === 'request_selesai')
            <div class="alert alert-warning mt-3 mb-0 py-2 px-3">
                {{ __('Request selesai dari petugas.') }}
            </div>
        @endif
    </div>

    {{-- CARD UBAH STATUS + AKSI --}}
    <div class="row g-4">
        {{-- UBAH STATUS --}}
        @auth
            @if(auth()->user()->isPetugas() || auth()->user()->isAdmin())
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header" style="background:#6366f1; color:white;">
                            <strong>{{ __('Ubah Status Laporan') }}</strong>
                        </div>
                        <div class="card-body pt-3 pb-3">
                            <form action="{{ route('laporan.updateStatus', ['locale' => $locale, 'laporan' => $laporan->id]) }}"
                                  method="POST" class="mb-0">
                                @csrf
                                @method('PATCH')

                                {{-- ADMIN: dropdown lengkap --}}
                                @if(auth()->user()->isAdmin())
                                    <div class="mb-3">
                                        <label for="status" class="form-label">{{ __('Status') }}</label>
                                        <select id="status" name="status" class="form-select">
                                            <option value="pending"
                                                {{ $laporan->status === 'pending' ? 'selected' : '' }}>
                                                {{ __('Menunggu') }}
                                            </option>
                                            <option value="diproses"
                                                {{ $laporan->status === 'diproses' ? 'selected' : '' }}>
                                                {{ __('Sedang Diproses') }}
                                            </option>
                                            <option value="selesai"
                                                {{ $laporan->status === 'selesai' ? 'selected' : '' }}>
                                                {{ __('Selesai') }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit"
                                                class="btn-pill btn-primary-clean">
                                            <i class="fas fa-save"></i> {{ __('Simpan Status') }}
                                        </button>
                                    </div>

                                {{-- PETUGAS: tombol sesuai kondisi --}}
                                @elseif(auth()->user()->isPetugas())
                                    @if($laporan->status === 'pending')
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit"
                                                class="btn-pill btn-primary-clean mt-1">
                                            <i class="fas fa-play"></i> {{ __('Set ke Diproses') }}
                                        </button>

                                    @elseif($laporan->status === 'diproses' && $laporan->status_request === 'none')
                                        <input type="hidden" name="status" value="request_selesai">
                                        <button type="submit"
                                                class="btn-pill btn-success-clean mt-1">
                                            <i class="fas fa-check-circle"></i>
                                            {{ __('Minta Set Selesai ke Admin') }}
                                        </button>

                                    @else
                                        <p class="text-muted mb-0 mt-2">
                                            {{ __('Tidak ada aksi status yang tersedia untuk petugas.') }}
                                        </p>
                                    @endif
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        {{-- AKSI (HEADER + TIGA TOMBOL SEBARIS) --}}
        <div class="mt-4">
            <div class="card-header" style="background:#059669; color:white;">
                <strong>{{ __('Aksi') }}</strong>
            </div>

            <div style="display:flex; align-items:center; gap:12px; padding:10px 12px 0 12px;">
                @auth
                    @if(auth()->user()->id === $laporan->user_id || auth()->user()->isAdmin())
                        <a href="{{ route('laporan.edit', ['locale' => $locale, 'laporan' => $laporan->id]) }}"
                           class="btn-pill btn-secondary-clean"
                           style="display:inline-block;">
                            {{ __('Edit') }}
                        </a>

                        <form action="{{ route('laporan.destroy', ['locale' => $locale, 'laporan' => $laporan->id]) }}"
                              method="POST"
                              style="display:inline-block; margin:0; padding:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('{{ __('Yakin hapus?') }}')"
                                    class="btn-pill btn-danger-clean"
                                    style="display:inline-block;">
                                {{ __('Hapus') }}
                            </button>
                        </form>
                    @endif
                @endauth

                <a href="{{ route('laporan.index', ['locale' => $locale]) }}"
                   class="btn-pill btn-secondary-clean"
                   style="display:inline-block;">
                    {{ __('Kembali') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
