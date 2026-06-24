<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ProductBranchDetailController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RestaurantBranchController;
use App\Http\Controllers\Admin\RestaurantController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', fn () => redirect()->route('admin.restaurants.index'));

// ── Auth (guest only) ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// ── Logout ─────────────────────────────────────────────────────────────────
Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout')->middleware('auth');

// ── Admin (auth required) ──────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.restaurants.index'));

    Route::resource('restaurants', RestaurantController::class)
        ->except(['show']);

    Route::resource('branches', RestaurantBranchController::class)
        ->except(['show']);

    Route::resource('products', ProductController::class)
        ->except(['show']);

    Route::resource('product-details', ProductBranchDetailController::class)
        ->except(['show'])
        ->parameters(['product-details' => 'detail']);
});
