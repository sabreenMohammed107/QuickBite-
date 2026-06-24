<?php

use App\Domains\Auth\Enums\UserRole;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductBranchDetailController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RestaurantBranchController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantMemberController;
use App\Http\Controllers\Merchant\BranchController as MerchantBranch;
use App\Http\Controllers\Merchant\CatalogController as MerchantCatalog;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboard;
use App\Http\Controllers\Merchant\MemberController as MerchantMember;
use App\Http\Controllers\Merchant\ProductController as MerchantProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Root: redirect based on auth state and role ────────────────────────────
Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        UserRole::Admin           => redirect()->route('admin.restaurants.index'),
        UserRole::RestaurantOwner => redirect()->route('merchant.dashboard'),
        default                   => redirect()->route('home'),
    };
});

// ── Unified login (guest only) ─────────────────────────────────────────────
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

// ── Admin panel (auth + admin role guard) ──────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.admin'])->group(function () {
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

    Route::resource('members', RestaurantMemberController::class)
        ->except(['show']);
});

// ── Merchant portal (auth + merchant role guard) ───────────────────────────
Route::prefix('merchant')->name('merchant.')->middleware(['auth', 'role.merchant'])->group(function () {
    Route::get('/dashboard', [MerchantDashboard::class, 'index'])->name('dashboard');

    Route::resource('products', MerchantProduct::class)->except(['show']);
    Route::resource('branches', MerchantBranch::class)->except(['show']);
    Route::resource('catalog',  MerchantCatalog::class)->except(['show'])
        ->parameters(['catalog' => 'detail']);
    Route::resource('members',  MerchantMember::class)->except(['show']);
});

// ── Customer home (auth, any role) ────────────────────────────────────────
Route::middleware('auth')->get('/home', fn () => view('home'))->name('home');
