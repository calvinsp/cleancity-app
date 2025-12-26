@extends('layouts.app')

@section('title', __('Profil Saya'))

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        {{ __('Profil') }}
    </h2>
@endsection

@section('content')
    @php
        $locale = request()->route('locale') ?? app()->getLocale();
    @endphp

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">

                {{-- Info akun --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Informasi Akun') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Perbarui nama dan email akun CleanCity kamu.') }}
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('profile.update', ['locale' => $locale]) }}"
                      class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Nama') }}
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', auth()->user()->name) }}"
                               class="form-control w-full">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Email') }}
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               class="form-control w-full">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-pill btn-primary-clean">
                            {{ __('Simpan Perubahan') }}
                        </button>
                        <a href="{{ route('home.dashboard', ['locale' => $locale]) }}"
                           class="btn-pill btn-secondary-clean">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>

                {{-- Ganti password --}}
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Keamanan') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Ubah password secara berkala untuk menjaga keamanan akun.') }}
                    </p>

                    <form method="POST"
                          action="{{ route('password.update', ['locale' => $locale]) }}"
                          class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Password Lama') }}
                            </label>
                            <input type="password" name="current_password" class="form-control w-full">
                            @error('current_password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Password Baru') }}
                            </label>
                            <input type="password" name="password" class="form-control w-full">
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Konfirmasi Password Baru') }}
                            </label>
                            <input type="password" name="password_confirmation" class="form-control w-full">
                        </div>

                        <button type="submit" class="btn-pill btn-primary-clean">
                            {{ __('Ubah Password') }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
