<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMarketplaceApplicationController;
use App\Http\Controllers\AdminMarketplaceController;
use App\Http\Controllers\MarketplaceApplicationController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketplaceMembershipController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Gate::define('platform-admin', fn ($user) => (bool) $user->is_platform_admin);

Route::inertia('/', 'Welcome')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/marketplace-applications', [MarketplaceApplicationController::class, 'index'])->name('marketplace-applications.index');
    Route::get('/marketplace-applications/create', [MarketplaceApplicationController::class, 'create'])->name('marketplace-applications.create');
    Route::post('/marketplace-applications', [MarketplaceApplicationController::class, 'store'])->name('marketplace-applications.store');
    Route::get('/marketplace-applications/{marketplaceApplication}', [MarketplaceApplicationController::class, 'show'])->name('marketplace-applications.show');
});

Route::middleware(['auth', 'marketplace'])->group(function () {
    Route::get('/marketplaces/{marketplace:slug}', [MarketplaceController::class, 'show'])
        ->name('marketplaces.show');
    Route::get('/marketplaces/{marketplace:slug}/memberships', [MarketplaceMembershipController::class, 'index'])
        ->name('marketplaces.memberships.index');
    Route::post('/marketplaces/{marketplace:slug}/memberships', [MarketplaceMembershipController::class, 'store'])
        ->name('marketplaces.memberships.store');
    Route::patch('/marketplaces/{marketplace:slug}/memberships/{membership}', [MarketplaceMembershipController::class, 'update'])
        ->name('marketplaces.memberships.update');
    Route::get('/marketplaces/{marketplace:slug}/products', [ProductController::class, 'index'])
        ->name('marketplaces.products.index');
    Route::get('/marketplaces/{marketplace:slug}/products/create', [ProductController::class, 'create'])
        ->name('marketplaces.products.create');
    Route::post('/marketplaces/{marketplace:slug}/products', [ProductController::class, 'store'])
        ->name('marketplaces.products.store');
    Route::get('/marketplaces/{marketplace:slug}/products/{product}', [ProductController::class, 'show'])
        ->name('marketplaces.products.show');
    Route::get('/marketplaces/{marketplace:slug}/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('marketplaces.products.edit');
    Route::patch('/marketplaces/{marketplace:slug}/products/{product}', [ProductController::class, 'update'])
        ->name('marketplaces.products.update');
    Route::post('/marketplaces/{marketplace:slug}/products/{product}/deactivate', [ProductController::class, 'deactivate'])
        ->name('marketplaces.products.deactivate');
});

Route::middleware(['auth', 'can:platform-admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/marketplace-applications', [AdminMarketplaceApplicationController::class, 'index'])->name('admin.marketplace-applications.index');
    Route::post('/admin/marketplace-applications/{marketplaceApplication}/approve', [AdminMarketplaceApplicationController::class, 'approve'])->name('admin.marketplace-applications.approve');
    Route::post('/admin/marketplace-applications/{marketplaceApplication}/reject', [AdminMarketplaceApplicationController::class, 'reject'])->name('admin.marketplace-applications.reject');
    Route::get('/admin/marketplaces', [AdminMarketplaceController::class, 'index'])->name('admin.marketplaces.index');
    Route::post('/admin/marketplaces/{marketplace}/activate', [AdminMarketplaceController::class, 'activate'])->name('admin.marketplaces.activate');
    Route::post('/admin/marketplaces/{marketplace}/suspend', [AdminMarketplaceController::class, 'suspend'])->name('admin.marketplaces.suspend');
    Route::post('/admin/marketplaces/{marketplace}/reactivate', [AdminMarketplaceController::class, 'reactivate'])->name('admin.marketplaces.reactivate');
});

require __DIR__.'/auth.php';
