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

            {{-- FILTER 30 HARI TERAKHIR --}}
            <div class="bg-white shadow-sm rounded-2xl p-6 mb-6 border border-gray-100">
                <h3 class="font-semibold text-lg mb-4">{{ __('Laporan 30 Hari Terakhir') }}</h3>

                <form method="GET" action="{{ route('laporan.index', ['locale' => $locale]) }}" class="flex gap-3 flex-wrap items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Dari Tanggal') }}</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Sampai Tanggal') }}</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                            {{ __('Cari') }}
                        </button>

                        <a href="{{ route('laporan.index', ['locale' => $locale]) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm hover:bg-gray-300 transition">
                            {{ __('Reset') }}
                        </a>

                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('laporan.export', ['from' => request('from'), 'to' => request('to')]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition">
                                    📥 {{ __('Export Laporan (CSV)') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                </form>
            </div>

            {{-- TABEL LAPORAN --}}
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
                                        {{ __('Tanggal Laporan') }}
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
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-700 font-medium">
                                            #{{ $item->id }}
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
                                        <td class="px-4 py-3">
                                            <span class="inline-block px-2 py-1 rounded text-xs font-medium
                                                @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($item->status === 'diproses') bg-blue-100 text-blue-800
                                                @elseif($item->status === 'selesai') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $item->getStatusLabel() }}
                                            </span>
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
