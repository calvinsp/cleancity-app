<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil locale dari route {locale} atau fallback ke config default
        $locale = $request->route('locale')
            ?? $request->session()->get('app_locale')
            ?? config('app.locale', 'id');

        // Simpan locale ke session supaya konsisten
        $request->session()->put('app_locale', $locale);

        return redirect()->route('home.dashboard', ['locale' => $locale]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Ambil locale sebelum session direset
        $locale = $request->route('locale')
            ?? $request->session()->get('app_locale')
            ?? config('app.locale', 'id');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login dengan locale yang sama
        return redirect()->route('login', ['locale' => $locale]);
    }
}
