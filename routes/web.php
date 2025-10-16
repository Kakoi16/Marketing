<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\CalendarController;
// Import semua controller yang dibutuhkan
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\RegisterStepController;
use App\Http\Controllers\Mahasiswa\BiodataController;
use App\Http\Controllers\Mahasiswa\PostRegisterController;
use App\Http\Controllers\Pdf\OCRController;
use App\Http\Controllers\Mahasiswa\VirtualAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute utama & dashboard umum
Route::get('/', fn() => view('welcome'));
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/post-register', [PostRegisterController::class, 'index'])->name('post-register');
Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chartData');

// === GRUP RUTE ADMIN ===
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::patch('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/userallprofile', [UserProfileController::class, 'index'])->name('userallprofile');
    Route::get('/userprofile/{id}', [UserProfileController::class, 'show'])->name('userprofile.show');

 Route::get('/promocode', [PromoCodeController::class, 'index'])->name('promocode.index');
Route::post('/promocode', [PromoCodeController::class, 'store'])->name('promocode.store');
Route::delete('/promocode/{id}', [PromoCodeController::class, 'destroy'])->name('promocode.destroy');
   Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    Route::get('/calendar/events', [CalendarController::class, 'getEvents']);
    Route::post('/calendar/store', [CalendarController::class, 'store']);
    Route::put('/calendar/update/{id}', [CalendarController::class, 'update']);
    Route::delete('/calendar/delete/{id}', [CalendarController::class, 'destroy']);

});


// === GRUP RUTE MAHASISWA ===
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Rute publik
    Route::get('/register-step', [RegisterStepController::class, 'index'])->name('register.step');
    Route::get('/register-promo', [RegisterStepController::class, 'showPromo'])->name('register.promo');
    Route::post('/upload', [RegisterStepController::class, 'uploadFiles'])->name('upload');
    Route::post('/validate', [RegisterStepController::class, 'validateFiles'])->name('validate');
    Route::post('/save-ocr', [RegisterStepController::class, 'saveCorrections'])->name('ocr.save');
    Route::get('/post-register', [PostRegisterController::class, 'index'])->name('post.register');
    // Rute privat (memerlukan login)
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/biodata', [BiodataController::class, 'create'])->name('biodata.create');
    });
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/biodata', [BiodataController::class, 'create'])->name('biodata.create');
        
        // PINDAHKAN KE SINI DAN PERBAIKI URL-NYA
        Route::get('/virtual-account', [VirtualAccountController::class, 'dashboard'])
             ->name('va.dashboard');
    });
});



// Grup Rute OCR
Route::prefix('ocr')->name('ocr.')->group(function () {
    Route::post('/scan', [OCRController::class, 'scan'])->name('scan');
    Route::post('/save', [OCRController::class, 'save'])->name('save');
});



// === RUTE PROFIL BAWAAN LARAVEL YANG HILANG ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

