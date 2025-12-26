@php
    $locale = $locale ?? (request()->route('locale') ?? app()->getLocale());
    $params = request()->route()?->parameters() ?? [];
    unset($params['locale']);
@endphp

<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <div class="flex items-center space-x-3">
                <a href="{{ route('home.dashboard', ['locale' => $locale]) }}" class="flex items-center">
                    <img src="{{ asset('images/CleanCity-logo.png') }}"
                         alt="CleanCity"
                         class="h-20 md:h-40 mr-4">
                </a>
            </div>

            <div class="flex items-center space-x-8 text-sm font-medium text-gray-700">
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home.dashboard', ['locale' => $locale]) }}" class="hover:text-blue-600 transition">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('laporan.index', ['locale' => $locale]) }}" class="hover:text-blue-600 transition">
                        {{ __('Laporan') }}
                    </a>
                    <a href="{{ route('notifications.index', ['locale' => $locale]) }}" class="hover:text-blue-600 transition">
                        {{ __('Notifikasi') }}
                    </a>
                    <a href="{{ route('about', ['locale' => $locale]) }}" class="hover:text-blue-600 transition">
                        {{ __('Tentang Kami') }}
                    </a>
                    <a href="{{ route('profile.edit', ['locale' => $locale]) }}" class="hover:text-blue-600 transition">
                        {{ __('Profil') }}
                    </a>

                    {{-- Tombol Logout --}}
                    <form method="POST" action="{{ route('logout', ['locale' => $locale]) }}">
                        @csrf
                        <button type="submit"
                                class="text-red-600 hover:text-red-700 transition">
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>

                <div>
                    <select
                        class="border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500"
                        onchange="if(this.value) window.location.href=this.value;"
                    >
                        <option
                            value="{{ route(Route::currentRouteName(), array_merge($params, ['locale' => 'id'])) }}"
                            {{ $locale === 'id' ? 'selected' : '' }}>
                            ID
                        </option>
                        <option
                            value="{{ route(Route::currentRouteName(), array_merge($params, ['locale' => 'en'])) }}"
                            {{ $locale === 'en' ? 'selected' : '' }}>
                            EN
                        </option>
                        <option
                            value="{{ route(Route::currentRouteName(), array_merge($params, ['locale' => 'zh'])) }}"
                            {{ $locale === 'zh' ? 'selected' : '' }}>
                            中文
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</nav>
