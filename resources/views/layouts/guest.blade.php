<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
@php
    $locale = request()->route('locale') ?? app()->getLocale();
@endphp

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
    <div class="mb-6">
        <a href="{{ route('home.dashboard', ['locale' => $locale]) }}">
            <img src="{{ asset('images/CleanCity-logo.png') }}"
                 alt="CleanCity"
                 class="h-20 md:h-40">
        </a>
    </div>

    <div class="w-full max-w-md bg-white shadow-md rounded-2xl px-6 py-6">
        {{ $slot }}
    </div>

    <x-footer />
</div>
</body>
</html>
