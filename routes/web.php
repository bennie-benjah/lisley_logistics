<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShipmentUpdateController;
use Illuminate\Support\Facades\Route;

// Public routes - accessible to everyone
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product routes (public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Service routes (public)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

// Shipment tracking (public)
Route::get('/track-shipment', [ShipmentController::class, 'track'])->name('shipments.track');
Route::post('/track-shipment', [ShipmentController::class, 'trackResult'])->name('shipments.track.result');

// Public category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/main', [CategoryController::class, 'mainCategories'])->name('categories.main');

// =================== NORMAL USER ROUTES ===================
// Protected with 'user' middleware - only for logged-in normal users (not admins)
Route::middleware(['auth', 'verified', 'user'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('home.index'); // Create this view for normal users
    })->name('dashboard');
    
    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart routes (user only)
    Route::get('/cart', [ProductController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add/{product}', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{product}', [ProductController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update/{product}', [ProductController::class, 'updateCart'])->name('cart.update');

    // User shipment management
    Route::get('/my-shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/my-shipments/{id}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');

    // Shipment updates for user's shipments
    Route::get('shipment-updates', [ShipmentUpdateController::class, 'index'])->name('shipment-updates.index');
    Route::get('shipment-updates/create', [ShipmentUpdateController::class, 'create'])->name('shipment-updates.create');
    Route::post('shipment-updates', [ShipmentUpdateController::class, 'store'])->name('shipment-updates.store');
    Route::get('shipment-updates/{id}', [ShipmentUpdateController::class, 'show'])->name('shipment-updates.show');
    Route::get('shipments/{id}/timeline', [ShipmentUpdateController::class, 'timeline'])->name('shipments.timeline');
    Route::get('api/shipments/{id}/updates', [ShipmentUpdateController::class, 'apiUpdates'])->name('api.shipments.updates');

    Route::get('/services/{id}/quote', [ServiceController::class, 'showQuote'])->name('services.quote');
Route::post('/services/{id}/quote', [ServiceController::class, 'submitQuote'])->name('services.quote.submit');
});

// =================== ADMIN ROUTES ===================
// Protected with 'admin' middleware - only for admin users
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    
    // Admin dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin products management
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    
    // Admin categories management
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('categories/tree', [CategoryController::class, 'getCategoryTree'])->name('categories.tree');
    Route::get('categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::get('categories/{id}/toggle-featured', [CategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    
    // Admin services management
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
    Route::get('/services/create', [AdminController::class, 'createService'])->name('admin.services.create');
    Route::post('/services', [AdminController::class, 'storeService'])->name('admin.services.store');
    Route::get('/services/{id}/edit', [AdminController::class, 'editService'])->name('admin.services.edit');
    Route::put('/services/{id}', [AdminController::class, 'updateService'])->name('admin.services.update');
    Route::delete('/services/{id}', [AdminController::class, 'destroyService'])->name('admin.services.destroy');
    
    // Admin shipments management (can view ALL shipments)
    Route::get('/shipments', [AdminController::class, 'shipments'])->name('admin.shipments');
    Route::get('/shipments/create', [AdminController::class, 'createShipment'])->name('admin.shipments.create');
    Route::post('/shipments', [AdminController::class, 'storeShipment'])->name('admin.shipments.store');
    Route::get('/shipments/{id}', [AdminController::class, 'showShipment'])->name('admin.shipments.show');
    Route::get('/shipments/{id}/edit', [AdminController::class, 'editShipment'])->name('admin.shipments.edit');
    Route::put('/shipments/{id}', [AdminController::class, 'updateShipment'])->name('admin.shipments.update');
    Route::delete('/shipments/{id}', [AdminController::class, 'destroyShipment'])->name('admin.shipments.destroy');
    
    // Admin shipment updates (full control)
    Route::resource('shipment-updates', ShipmentUpdateController::class)->except(['index', 'create', 'store']);
    Route::post('shipment-updates/bulk', [ShipmentUpdateController::class, 'bulkUpdate'])->name('shipment-updates.bulk');
    Route::get('shipment-updates/driver/{driverId}', [ShipmentUpdateController::class, 'byDriver'])->name('shipment-updates.by-driver');
    Route::get('shipment-updates/export', [ShipmentUpdateController::class, 'export'])->name('shipment-updates.export');
    Route::get('shipment-updates/statistics', [ShipmentUpdateController::class, 'statistics'])->name('shipment-updates.statistics');
    Route::get('dashboard/recent-updates', [ShipmentUpdateController::class, 'recentUpdates'])->name('dashboard.recent-updates');
    
    // Admin orders management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    
    // Admin customers management
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/customers/{id}', [AdminController::class, 'showCustomer'])->name('admin.customers.show');
    
    // Admin reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/generate/{type}', [AdminController::class, 'generateReport'])->name('admin.reports.generate');
    
    // Admin settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

// =================== AUTH ROUTES ===================
// These use Laravel's built-in auth middleware
require __DIR__.'/auth.php';

// Fallback route for 404 pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});