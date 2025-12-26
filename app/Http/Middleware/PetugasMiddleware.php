<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PetugasMiddleware {
    public function handle(Request $request, Closure $next): Response {
        if (auth()->check() && (auth()->user()->isPetugas() || auth()->user()->isAdmin())) {
            return $next($request);
        }
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
