{{-- resources/views/auth/login.blade.php --}}

<x-guest-layout>
    @php
        $locale = request()->route('locale') ?? app()->getLocale();
    @endphp

    <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">
        {{ __('Masuk ke Akun') }}
    </h2>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login', ['locale' => $locale]) }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Email') }}
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required autofocus
                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Password') }}
            </label>
            <input id="password" type="password" name="password" required
                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex items-center justify-between mb-4">
            <label class="inline-flex items-center text-sm text-gray-600">
                <input type="checkbox" name="remember"
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="ml-2">{{ __('Ingat saya') }}</span>
            </label>

            <a href="{{ route('password.request', ['locale' => $locale]) }}"
               class="text-sm text-blue-600 hover:underline">
                {{ __('Lupa password?') }}
            </a>
        </div>

        <button type="submit"
                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            {{ __('Masuk') }}
        </button>
    </form>
</x-guest-layout>
