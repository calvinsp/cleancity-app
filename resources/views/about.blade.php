@extends('layouts.app')

@section('title', 'About Us')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight tracking-tight">
            About Us
        </h2>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
            SDG 11 – Sustainable Cities & Communities
        </span>
    </div>
@endsection

@section('content')
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Intro / Description --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 sm:p-8 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                    Tentang CleanCity
                </h3>
                <p class="text-gray-700 leading-relaxed mb-3">
                    CleanCity adalah platform pelaporan kebersihan kota yang membantu masyarakat dan warga kampus
                    menyampaikan masalah kebersihan secara cepat dan terstruktur.
                </p>
                <p class="text-gray-700 leading-relaxed mb-3">
                    Melalui CleanCity, pengguna dapat melaporkan tumpukan sampah, limbah berbau, TPS penuh,
                    atau gangguan kebersihan lainnya sehingga pihak terkait dapat menindaklanjuti dengan lebih efisien.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Proyek ini sejalan dengan <span class="font-semibold">SDG 11 (Sustainable Cities & Communities)</span>,
                    dengan tujuan mendukung terciptanya lingkungan kota yang lebih layak huni, bersih, dan berkelanjutan.
                </p>
            </div>

            {{-- Team Section --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 sm:p-8 border border-gray-100 mt-6">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Kelompok 8
                    </h3>
                    <p class="text-sm text-gray-600">
                        Proyek Web Programming – BINUS University
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6 items-stretch">
                    <div class="flex flex-col items-center justify-center text-center border border-gray-100 rounded-2xl py-6 px-4">
                        <p class="text-sm font-medium text-gray-500">Anggota 1</p>
                        <p class="mt-1 text-base font-semibold text-gray-900">
                            Lie, Calvin Sugiarto Prabowo
                        </p>
                        <p class="mt-1 text-xs inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-medium">
                            NIM: 2702236244
                        </p>
                    </div>

                    <div class="flex flex-col items-center justify-center text-center border border-gray-100 rounded-2xl py-6 px-4">
                        <p class="text-sm font-medium text-gray-500">Anggota 2</p>
                        <p class="mt-1 text-base font-semibold text-gray-900">
                            Kevin Jeremia
                        </p>
                        <p class="mt-1 text-xs inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-medium">
                            NIM: 2702292932
                        </p>
                    </div>

                    <div class="flex flex-col items-center justify-center text-center border border-gray-100 rounded-2xl py-6 px-4">
                        <p class="text-sm font-medium text-gray-500">Anggota 3</p>
                        <p class="mt-1 text-base font-semibold text-gray-900">
                            Muhammad Rafi Fadhilah
                        </p>
                        <p class="mt-1 text-xs inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-medium">
                            NIM: 2702263112
                        </p>
                    </div>
                </div>
            </div>

            {{-- SDG Highlight --}}
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-emerald-900">
                        Kontribusi terhadap SDG 11
                    </h3>
                    <p class="mt-1 text-sm text-emerald-900/80 leading-relaxed">
                        Dengan mempermudah pelaporan masalah kebersihan seperti tumpukan sampah,
                        limbah berbau, atau TPS yang penuh, CleanCity membantu terciptanya kota
                        dan komunitas kampus yang lebih aman, nyaman, dan berkelanjutan.
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
