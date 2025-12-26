@extends('layouts.app')

@section('title', __('Dashboard'))

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    @php
        $locale = request()->route('locale') ?? app()->getLocale();
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">

        <div class="bg-white shadow-sm rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                {{ __('Laporan Terakhir') }}
            </h3>

            {{-- Ringkasan statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                @isset($totalLaporan)
                    <div class="p-3 rounded-xl bg-blue-50">
                        <p class="text-xs text-gray-500">{{ __('Total Laporan') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalLaporan }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-yellow-50">
                        <p class="text-xs text-gray-500">{{ __('Pending') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanPending }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-sky-50">
                        <p class="text-xs text-gray-500">{{ __('Diproses') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanDiproses }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50">
                        <p class="text-xs text-gray-500">{{ __('Selesai') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanSelesai }}</p>
                    </div>
                @else
                    <div class="p-3 rounded-xl bg-blue-50">
                        <p class="text-xs text-gray-500">{{ __('Laporan Saya') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanSaya ?? 0 }}</p>
                    </div>
                @endisset
            </div>

            {{-- List 5 laporan terbaru --}}
            <div class="border-t border-gray-100 pt-4">
                @if($recentReports->isEmpty())
                    <p class="text-sm text-gray-500">
                        {{ __('Belum ada laporan.') }}
                    </p>
                @else
                    <ul class="divide-y divide-gray-100 text-sm">
                        @foreach($recentReports as $laporan)
                            <li class="py-2 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $laporan->jenisSampah?->getTranslatedName() ?? __('Laporan #:id', ['id' => $laporan->id]) }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $laporan->lokasi?->getTranslatedName() ?? '-' }} •
                                        {{ $laporan->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                                    {{ $laporan->getStatusLabel() }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('laporan.index', ['locale' => $locale]) }}"
                   class="text-sm text-blue-600 hover:underline">
                    {{ __('Lihat semua laporan') }}
                </a>
            </div>
        </div>

        {{-- Ringkasan laporan 30 hari terakhir per jenis sampah (hanya kalau datanya dikirim) --}}
        @if(!empty($reportsByJenisLast30) && $reportsByJenisLast30 instanceof \Illuminate\Support\Collection && $reportsByJenisLast30->isNotEmpty())
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ __('Laporan 30 Hari Terakhir per Jenis Sampah') }}
                </h3>

                <div class="overflow-x-auto text-sm">
                    <table class="min-w-full border border-gray-100 rounded-xl overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                    {{ __('Jenis Sampah') }}
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                    {{ __('Total Laporan') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($reportsByJenisLast30 as $row)
                                <tr>
                                    <td class="px-4 py-2">
                                        {{ $row->jenisSampah?->getTranslatedName() ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $row->total }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="mt-2 text-xs text-gray-500">
                    {{ __('Data di atas berdasarkan laporan yang dibuat dalam 30 hari terakhir.') }}
                </p>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-2xl p-6 flex flex-wrap gap-3">
            <a href="{{ route('laporan.create', ['locale' => $locale]) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                {{ __('Buat Laporan Baru') }}
            </a>

            <a href="{{ route('notifications.index', ['locale' => $locale]) }}"
               class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg text-sm hover:bg-gray-200 transition">
                {{ __('Lihat Notifikasi') }}
            </a>
            
        @if(isset($role) && $role === 'admin')
                <a href="{{ route('laporan.export', ['locale' => $locale]) }}"
                   class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700 transition">
                    {{ __('Export Laporan (CSV)') }}
                </a>
            @endif
        </div>
    </div>
@endsection
