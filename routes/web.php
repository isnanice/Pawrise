<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shelter\DashboardController as ShelterDashboard;
use App\Http\Controllers\Shelter\AnimalController as ShelterAnimalController;
use App\Http\Controllers\Shelter\ApplicationController as ShelterApplicationController;
// ── Admin ────────────────────────────────────────────────────────────────────
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ShelterController as AdminShelterController;
use App\Http\Controllers\Admin\EdukasiController as AdminEdukasiController;

// ============================================================
// Public
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/edukasi', [EducationController::class, 'index'])->name('education');
Route::get('/edukasi/{kontenEdukasi}', [EducationController::class, 'show'])->name('education.show');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/bantuan', [HomeController::class, 'help'])->name('help');
Route::post('/kontak', [HomeController::class, 'sendContact'])->name('contact.send');
Route::get('/kebijakan-privasi', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/syarat-ketentuan', [HomeController::class, 'terms'])->name('terms');
Route::get('/kontak-shelter', [HomeController::class, 'shelterContact'])->name('shelterContact');
Route::get('/gabung-relawan', [HomeController::class, 'volunteer'])->name('volunteer');

// ============================================================
// Auth
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'show'])->name('login');
    Route::post('/masuk', [LoginController::class, 'store'])->name('login.store');
    Route::get('/daftar', [RegisterController::class, 'show'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'store'])->name('register.store');

    // Lupa & Atur Ulang Kata Sandi
    Route::get('/lupa-kata-sandi', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/lupa-kata-sandi', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/atur-ulang-kata-sandi/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/atur-ulang-kata-sandi', [NewPasswordController::class, 'store'])->name('password.store');
});

// ============================================================
// Catalog & Animals (Requires Login)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/hewan/{animal}', [AnimalController::class, 'show'])->name('animals.show');

    Route::get('/keluar', [LogoutController::class, 'show'])->name('logout.show');
    Route::post('/keluar', [LogoutController::class, 'destroy'])->name('logout');
});

// ============================================================
// Adopter
// ============================================================
Route::middleware(['auth', 'role:adopter'])->group(function () {
    Route::post('/favorit/{animal}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/adopsi/{animal}', [AdoptionController::class, 'create'])->name('adoption.create');
    Route::post('/adopsi/{animal}', [AdoptionController::class, 'store'])->name('adoption.store');

    Route::prefix('akun')->name('user.')->group(function () {
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile');
        Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/permohonan-saya', [AdoptionController::class, 'index'])->name('applications');
        Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites');
    });
});

// ============================================================
// Shelter
// ============================================================
Route::middleware(['auth', 'role:shelter'])->prefix('shelter')->name('shelter.')->group(function () {
    Route::get('/dashboard', [ShelterDashboard::class, 'index'])->name('dashboard');

    Route::get('/animals/trash', [ShelterAnimalController::class, 'trash'])->name('animals.trash');
    Route::post('/animals/{id}/restore', [ShelterAnimalController::class, 'restore'])->name('animals.restore');
    Route::delete('/animals/{id}/force-delete', [ShelterAnimalController::class, 'forceDelete'])->name('animals.force-delete');
    Route::resource('animals', ShelterAnimalController::class)->except(['show']);

    Route::get('/permohonan', [ShelterApplicationController::class, 'index'])->name('applications.index');
    Route::get('/permohonan/{application}', [ShelterApplicationController::class, 'show'])->name('applications.show');
    Route::post('/permohonan/{application}/setujui', [ShelterApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/permohonan/{application}/tolak', [ShelterApplicationController::class, 'reject'])->name('applications.reject');
});

Route::get('/shelter/{shelter}', [HomeController::class, 'shelterProfile'])->name('shelter.profile');

// ============================================================
// Admin
// ============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Kelola Pengguna
    Route::get('/pengguna', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/pengguna/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::delete('/pengguna/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Verifikasi Shelter
    Route::get('/shelter', [AdminShelterController::class, 'index'])->name('shelters.index');
    Route::get('/shelter/{shelter}', [AdminShelterController::class, 'show'])->name('shelters.show');
    Route::post('/shelter/{shelter}/verifikasi', [AdminShelterController::class, 'verify'])->name('shelters.verify');
    Route::post('/shelter/{shelter}/tolak', [AdminShelterController::class, 'reject'])->name('shelters.reject');
    Route::delete('/shelter/{shelter}', [AdminShelterController::class, 'destroy'])->name('shelters.destroy');

    // Kelola Konten Edukasi
    Route::get('/edukasi/trash', [AdminEdukasiController::class, 'trash'])->name('edukasi.trash');
    Route::post('/edukasi/{id}/restore', [AdminEdukasiController::class, 'restore'])->name('edukasi.restore');
    Route::delete('/edukasi/{id}/force-delete', [AdminEdukasiController::class, 'forceDelete'])->name('edukasi.force-delete');
    Route::resource('edukasi', AdminEdukasiController::class)->parameters(['edukasi' => 'edukasi']);
});