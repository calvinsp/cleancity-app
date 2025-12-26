{{-- resources/views/laporan/index.blade.php --}}

@extends('layouts.app')

@section('title', __('Daftar Laporan'))

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        {{ __('Laporan') }}
    </h2>
@endsection

@section('content')
    @php
        $locale = request()->route('locale') ?? app()->getLocale();
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header + tombol buat laporan --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ __('Daftar Laporan') }}
                </h3>

                <a href="{{ route('laporan.create', ['locale' => $locale]) }}"
                   class="btn-pill btn-primary-clean">
                    {{ __('Buat Laporan Baru') }}
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                @if ($laporan->count() === 0)
                    <div class="p-6">
                        <p class="text-sm text-gray-500">
                            {{ __('Belum ada laporan yang tercatat.') }}
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        {{ __('ID') }}
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        {{ __('Jenis Sampah') }}
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        {{ __('Lokasi') }}
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        {{ __('Tanggal') }}
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        {{ __('Aksi') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($laporan as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $item->id }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $item->jenisSampah?->getTranslatedName() ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $item->lokasi?->getTranslatedName() ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $item->created_at?->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $item->getStatusLabel() }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('laporan.show', ['locale' => $locale, 'laporan' => $item->id]) }}"
                                               class="btn-pill btn-secondary-clean inline-block">
                                                {{ __('Detail') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $laporan->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
