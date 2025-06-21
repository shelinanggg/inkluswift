<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\OrderHistoryController;


Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Route::get('/home', function () {
//     return view('home');
// })->name('home');

Route::get('/description', function () {
    return view('description');
})->name('description');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/status', function () {
    return view('status');
})->name('status');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/history', function () {
    return view('history');
})->name('history');

Route::get('/paymethod', function () {
    return view('paymethod');
})->name('paymethod');

Route::get('/setting', function () {
    return view('setting');
})->name('setting');

Route::get('/information', function () {
    return view('information');
})->name('information');

Route::get('/admin', function () {
    return view('admin');
})->name('admin');

Route::get('/order', function () {
    return view('order');
})->name('order');

Route::get('/menu', function () {
    return view('menu');
})->name('menu');

Route::get('/revenue', function () {
    return view('revenue');
})->name('revenue');

Route::get('/account', function () {
    return view('account');
})->name('account');

// Route Account Management
Route::get('/account-management', [AccountController::class, 'index'])->name('account.index');

// API untuk CRUD Account
Route::prefix('api/accounts')->group(function () {
    Route::get('/', [AccountController::class, 'getUsers'])->name('accounts.get');
    Route::post('/', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/{id}', [AccountController::class, 'show'])->name('accounts.show');
    Route::put('/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
});

Route::prefix('api')->group(function () {
    Route::get('/menus', [MenuController::class, 'getMenus']);
    Route::post('/menus', [MenuController::class, 'store']);
    Route::get('/menus/{id}', [MenuController::class, 'show']);
    Route::put('/menus/{id}', [MenuController::class, 'update']);
    Route::delete('/menus/{id}', [MenuController::class, 'destroy']);
    Route::get('/menu-categories', [MenuController::class, 'getCategories']);
});

// Route Payment Management
Route::get('/payment-management', [PaymentController::class, 'index'])->name('payment');

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/payments', [PaymentController::class, 'getPayments']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::put('/payments/{id}', [PaymentController::class, 'update']);
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);
});

// Home routes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');
Route::get('/category/{category}', [HomeController::class, 'getByCategory'])->name('home.category');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Description routes
Route::get('/description', [DescriptionController::class, 'index'])->name('description');
Route::post('/description/add-to-cart', [DescriptionController::class, 'addToCart'])->name('description.add-to-cart');

// Cart routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/remove', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::get('/checkout', [CartController::class, 'getCartForCheckout'])->name('cart.checkout');
});

// Alternative route for backward compatibility
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// Checkout routes
Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/payment-method/{methodId}', [CheckoutController::class, 'getPaymentMethod'])->name('checkout.payment-method');
});

// Alternative route for backward compatibility
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

// Pastikan route ini ada juga untuk cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/remove', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('cart.count');
});

// Alternative route for backward compatibility
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// routes/web.php
Route::get('/edit-profile', [EditProfileController::class, 'showEditForm'])->name('edit-profile');
Route::post('/edit-profile', [EditProfileController::class, 'updateProfile'])->name('update-profile');
Route::get('/change-password', [EditProfileController::class, 'showChangePasswordForm'])->name('change-password');
Route::post('/change-password', [EditProfileController::class, 'updatePassword'])->name('update-password');
Route::delete('/profile-picture', [EditProfileController::class, 'removeProfilePicture'])->name('remove-profile-picture');

Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order-history.index');
Route::get('/order-history/{orderId}', [OrderHistoryController::class, 'show'])->name('order-history.show');
Route::get('/order-history/filter/status', [OrderHistoryController::class, 'filterByStatus'])->name('order-history.filter');
Route::post('/order-history/{orderId}/cancel', [OrderHistoryController::class, 'cancel'])->name('order-history.cancel');
Route::post('/order-history/{orderId}/reorder', [OrderHistoryController::class, 'reorder'])->name('order-history.reorder');