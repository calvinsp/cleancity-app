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
            <form method="POST" action="{{ route('laporan.store', ['locale' => $locale]) }}">
                @csrf

                {{-- Jenis Sampah --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Jenis Sampah') }}
                    </label>
                    <select name="jenis_sampah_id"
                            class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($jenisSampah as $jenis)
                            <option value="{{ $jenis->id }}" @selected(old('jenis_sampah_id') == $jenis->id)>
                                {{ $jenis->getTranslatedName() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lokasi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Lokasi') }}
                    </label>
                    <select name="lokasi_id"
                            class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($lokasi as $lokasiItem)
                            <option value="{{ $lokasiItem->id }}" @selected(old('lokasi_id') == $lokasiItem->id)>
                                {{ $lokasiItem->getTranslatedName() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Deskripsi') }}
                    </label>
                    <textarea name="deskripsi" rows="4"
                              class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') }}</textarea>
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
@endsection
