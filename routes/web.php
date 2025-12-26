<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\JenisSampahController;
use App\Http\Controllers\LokasiTpsController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Redirect root ke locale default
|--------------------------------------------------------------------------
| /  ->  /{locale}/dashboard
*/
Route::get('/', function () {
    $locale = config('app.locale', 'en');

    App::setLocale($locale);

    return redirect()->route('home.dashboard', ['locale' => $locale]);
});

/*
|--------------------------------------------------------------------------
| Semua route dengan prefix {locale}
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix'     => '{locale}',
    'where'      => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => ['setlocale'],
], function () {

    // Auth (Laravel Breeze) — semua route di auth.php jadi /{locale}/...
    require __DIR__ . '/auth.php';

    // Halaman statis (public)
    Route::view('/about', 'about')->name('about');

    /*
    |--------------------------------------------------------------------------
    | Routes yang butuh login
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('home.dashboard');

        // Laporan + update status
        Route::resource('laporan', LaporanController::class);
        Route::patch('/laporan/{laporan}/status', [LaporanController::class, 'updateStatus'])
            ->name('laporan.updateStatus');

        // Profil
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        /*
        |--------------------------------------------------------------------------
        | Route khusus ADMIN
        |--------------------------------------------------------------------------
        */
        Route::middleware('admin')->group(function () {

            Route::get('/notifications', function () {
                $user = auth()->user();
                $notifications = $user->notifications()->latest()->get();

                return view('notifications.index', compact('notifications'));
            })->name('notifications.index');

            Route::post('/notifications/{notification}/read', function ($notificationId) {
                $notification = auth()->user()
                    ->notifications()
                    ->where('id', $notificationId)
                    ->firstOrFail();

                $notification->markAsRead();

                return back();
            })->name('notifications.read');

            Route::resource('jenis-sampah', JenisSampahController::class);
            Route::resource('lokasi', LokasiTpsController::class);
            Route::resource('pengumuman', PengumumanController::class);
        });
    });
});
