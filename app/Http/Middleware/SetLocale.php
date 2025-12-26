<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil route saat ini (bisa null di beberapa konteks)
        $route = $request->route();

        // Ambil locale dari parameter {locale} kalau ada,
        // kalau tidak ada pakai yang tersimpan di session,
        // terakhir fallback ke config('app.locale', 'en')
        $locale = $route?->parameter('locale')
            ?? $request->session()->get('app_locale')
            ?? config('app.locale', 'en');

        // Validasi locale terhadap daftar yang diperbolehkan
        $allowedLocales = config('app.locales', ['id', 'en']);

        if (! in_array($locale, $allowedLocales, true)) {
            $locale = config('app.locale', 'en');
        }

        // Simpan pilihan locale di session
        $request->session()->put('app_locale', $locale);

        // Set locale aplikasi
        App::setLocale($locale);

        // Set default parameter 'locale' untuk SEMUA route() dan redirect()->route()
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
