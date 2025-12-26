@extends('layouts.app')

@section('title', __('Buat Laporan'))

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        {{ __('Buat Laporan') }}
    </h2>
@endsection

@section('content')
    @php
        $locale = request()->route('locale') ?? app()->getLocale();
    @endphp

    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-sm rounded-2xl p-6">
            <form method="POST" action="{{ route('laporan.store', ['locale' => $locale]) }}" enctype="multipart/form-data">
                @csrf

                {{-- Jenis Sampah --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Jenis Sampah') }}
                    </label>
                    <select name="jenis_sampah_id"
                            class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 @error('jenis_sampah_id') border-red-500 @enderror">
                        <option value="">{{ __('Pilih Jenis Sampah') }}</option>
                        @foreach($jenisSampah as $jenis)
                            <option value="{{ $jenis->id }}" @selected(old('jenis_sampah_id') == $jenis->id)>
                                {{ $jenis->getTranslatedName() }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_sampah_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Lokasi') }}
                    </label>
                    <select name="lokasi_id"
                            class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 @error('lokasi_id') border-red-500 @enderror">
                        <option value="">{{ __('Pilih Lokasi') }}</option>
                        @foreach($lokasi as $lokasiItem)
                            <option value="{{ $lokasiItem->id }}" @selected(old('lokasi_id') == $lokasiItem->id)>
                                {{ $lokasiItem->getTranslatedName() }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Deskripsi') }}
                    </label>
                    <textarea name="deskripsi" rows="4"
                              class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto (OPSIONAL) --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Foto Bukti') }}
                        <span class="text-gray-500 font-normal">({{ __('Opsional') }})</span>
                    </label>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 @error('foto') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-2">
                        {{ __('Format: JPG, PNG, GIF. Ukuran maksimal: 2MB') }}
                    </p>
                    @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview Foto --}}
                <div id="preview" class="mb-4" style="display: none;">
                    <img id="preview-img" src="" alt="Preview" class="rounded-lg max-w-xs">
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('laporan.index', ['locale' => $locale]) }}"
                       class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg text-sm hover:bg-gray-200 transition">
                        {{ __('Batal') }}
                    </a>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                        {{ __('Simpan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const previewImg = document.getElementById('preview-img');

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
@endsection
