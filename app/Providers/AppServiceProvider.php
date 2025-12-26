<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ambil locale dari segment pertama URL: /{locale}/...
        // kalau tidak ada, pakai default dari config('app.locale', 'en')
        $locale = Request::segment(1) ?: config('app.locale', 'en');

        // Validasi terhadap daftar locale yang diperbolehkan
        $allowedLocales = config('app.locales', ['id', 'en', 'zh']);

        if (! in_array($locale, $allowedLocales, true)) {
            $locale = config('app.locale', 'en');
        }

        // Set locale aplikasi
        App::setLocale($locale);

        // SIMPAN ke session supaya bisa dipakai dari tempat lain (job, dsb.)
        session(['app_locale' => $locale]);

        // PENTING: set default parameter 'locale' untuk semua route()
        // sehingga route('login'), route('password.request'), dll
        // otomatis menyertakan {locale}
        URL::defaults(['locale' => $locale]);
    }
}
