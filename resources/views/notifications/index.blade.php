@extends('layouts.app')

@section('title', __('Notifikasi'))

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        {{ __('Notifikasi') }}
    </h2>
@endsection

@section('content')
@php
    $locale = request()->route('locale') ?? app()->getLocale();
@endphp

<div class="max-w-4xl mx-auto">
    @forelse ($notifications as $notification)
        <div class="bg-white shadow-sm rounded-2xl p-6 mb-4 flex justify-between items-start">
            <div>
                <h3 class="font-semibold text-gray-900">
                    {{ $notification->data['title'] ?? __('Permintaan selesai dari petugas') }}
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $notification->data['message']
                        ?? __('Petugas meminta konfirmasi selesai untuk laporan #:id.', [
                            'id' => $notification->data['laporan_id'] ?? '?',
                        ]) }}
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    {{ $notification->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="ml-6 flex flex-col items-end space-y-2">
                <a href="{{ $notification->data['url'] ?? '#' }}"
                   class="text-blue-600 text-sm font-medium hover:underline">
                    {{ __('Lihat') }}
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white shadow-sm rounded-2xl p-8 text-center text-gray-500">
            {{ __('Belum ada notifikasi.') }}
        </div>
    @endforelse
</div>
@endsection
