<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PendingApprovals;
use App\Livewire\Admin\ProfessionalManager;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\ReviewModeration;
use App\Livewire\Admin\Analytics;
use App\Livewire\Admin\Settings;
use App\Livewire\Dashboard\MyDashboard;
use App\Livewire\Homepage;
use App\Livewire\Professionals\Index;
use App\Livewire\Professionals\Show;
use App\Livewire\Dashboard\ProfessionalDashboard;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\CheckUserStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

// ===== Public routes =====
Route::get('/', Homepage::class)->name('home');
Route::get('/professionals', Index::class)->name('professionals.index');
Route::get('/professionals/{slug}', Show::class)->name('professionals.show');

// ===== Auth routes =====
Route::middleware(['guest', 'throttle:login'])->group(function () {
    Route::get('/login',           Login::class)->name('login');
    Route::get('/register',        Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

// ===== Professional dashboard =====
Route::get('/pro/dashboard', MyDashboard::class)
    ->middleware(['auth', 'check.status'])
    ->name('pro.dashboard');

// ===== Admin routes =====
Route::middleware(['auth', 'check.status', EnsureAdmin::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard',     AdminDashboard::class)->name('admin.dashboard');
        Route::get('/approvals',     PendingApprovals::class)->name('admin.approvals');
        Route::get('/professionals', ProfessionalManager::class)->name('admin.professionals');
        Route::get('/categories',    CategoryManager::class)->name('admin.categories');
        Route::get('/reviews',       ReviewModeration::class)->name('admin.reviews');
        Route::get('/analytics',     Analytics::class)->name('admin.analytics');
        Route::get('/settings',      Settings::class)->name('admin.settings');
    });

// ===== Legacy dashboard (backward compat) =====
Route::get('/dashboard/{id}', ProfessionalDashboard::class)->name('dashboard');
