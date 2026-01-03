<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\QuotesController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentUpdateController;
use Illuminate\Support\Facades\Route;

// Public routes - accessible to everyone
// Public home page
Route::get('/', [HomeController::class, 'index'])->name('home');


// Product routes (public)
// Public products page
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Service routes (public)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services', [ServiceController::class, 'servicesPage']);
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
// Services API route
Route::get('/api/services', [ServiceController::class, 'apiServices']);

// Shipment tracking (public)
Route::get('/track-shipment', [ShipmentController::class, 'track'])->name('shipments.track');
Route::post('/track-shipment', [ShipmentController::class, 'trackResult'])->name('shipments.track.result');

// Public category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');


Route::get('/api/categories', [CategoryController::class, 'apiIndex']);

Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/main', [CategoryController::class, 'mainCategories'])->name('categories.main');
Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
// =================== NORMAL USER ROUTES ===================
// Protected with 'user' middleware - only for logged-in normal users (not admins)
Route::middleware(['auth', 'verified', 'user'])->group(function () {

    // Dashboard
 Route::get('/dashboard', [HomeController::class, 'products'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
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

    // Admin categories management
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('categories/tree', [CategoryController::class, 'getCategoryTree'])->name('categories.tree');
    Route::get('categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::get('categories/{id}/toggle-featured', [CategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // Add the index route explicitly
    Route::get('services', [ServicesController::class, 'index'])->name('services.index');

    // Then your resource route (excluding index since we already defined it)
    Route::resource('services', ServicesController::class)->except(['index']);

    // Or if you want the resource to create all routes including index:
    // Route::resource('services', ServicesController::class);

    // Your API routes...
    Route::get('services/api/list', [ServicesController::class, 'apiIndex'])->name('services.api.index');
    Route::get('services/api/{service}', [ServicesController::class, 'apiShow'])->name('services.api.show');
    Route::post('services/{service}/toggle-status', [ServicesController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::post('services/bulk-action', [ServicesController::class, 'bulkAction'])->name('services.bulk-action');
    Route::get('services/export', [ServicesController::class, 'export'])->name('services.export');
    Route::get('/products/data', [ProductsController::class, 'data']);
    Route::post('/products', [ProductsController::class, 'store']);
    Route::put('/products/{product}', [ProductsController::class, 'update']);
    Route::delete('/products/{product}', [ProductsController::class, 'destroy']); // Admin shipments management (can view ALL shipments)
    Route::get('/shipments', [AdminController::class, 'shipments'])->name('admin.shipments');
    Route::get('/shipments/create', [AdminController::class, 'createShipment'])->name('admin.shipments.create');
    Route::post('/shipments', [AdminController::class, 'storeShipment'])->name('admin.shipments.store');
    Route::get('/shipments/{id}', [AdminController::class, 'showShipment'])->name('admin.shipments.show');
    Route::get('/shipments/{id}/edit', [AdminController::class, 'editShipment'])->name('admin.shipments.edit');
    Route::put('/shipments/{id}', [AdminController::class, 'updateShipment'])->name('admin.shipments.update');
    Route::delete('/shipments/{id}', [AdminController::class, 'destroyShipment'])->name('admin.shipments.destroy');

    // Admin shipment updates (full control)
   Route::get('/shipments', [ShipmentController::class, 'indexApi']);
    Route::get('/customers', [CustomersController::class, 'indexApi']); // Quotes routes

    Route::get('/quotes/data', [QuotesController::class, 'data'])->name('admin.quotes.data');
    Route::get('/quotes/stats', [QuotesController::class, 'stats'])->name('admin.quotes.stats');
    Route::post('/quotes/{quote}/status', [QuotesController::class, 'updateStatus'])->name('admin.quotes.status');
    Route::delete('/quotes/{quote}', [QuotesController::class, 'destroy'])->name('admin.quotes.destroy');
    Route::post('/quotes/bulk-action', [QuotesController::class, 'bulkAction'])->name('admin.quotes.bulk');
    // Admin orders management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');

   // Add to your admin routes group
Route::get('/customers', [CustomersController::class, 'index'])->name('admin.customers');
Route::get('/customers/data', [CustomersController::class, 'data']);
Route::get('/customers/stats', [CustomersController::class, 'stats']);
Route::post('/customers', [CustomersController::class, 'store']);
Route::put('/customers/{customer}', [CustomersController::class, 'update']);
Route::delete('/customers/{customer}', [CustomersController::class, 'destroy']);    // Admin reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/generate/{type}', [AdminController::class, 'generateReport'])->name('admin.reports.generate');

    // Admin settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

// =================== AUTH ROUTES ===================
// These use Laravel's built-in auth middleware
require __DIR__ . '/auth.php';

// Fallback route for 404 pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
