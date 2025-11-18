<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalogue et détails ebooks
Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks.index');
Route::get('/ebooks/{slug}', [EbookController::class, 'show'])->name('ebooks.show');

// Panier
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/panier/retirer/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/panier/vider', [CartController::class, 'clear'])->name('cart.clear');

// Paiement Paydunya
Route::get('/commander', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commander/initier', [CheckoutController::class, 'initiate'])->name('checkout.initiate');
Route::get('/commander/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
Route::post('/commander/kkiapay', [CheckoutController::class, 'processKkiapay'])->name('checkout.kkiapay');
Route::get('/commande/confirmation/{numeroCommande}', [CheckoutController::class, 'success'])->name('checkout.success');

// FedaPay (optionnel)
Route::post('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/success', fn() => view('payment.success'))->name('payment.success');
Route::get('/payment/failed', fn() => view('payment.failed'))->name('payment.failed');

// Login Admin
Route::get('/secureadmin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/secureadmin/login', function (Request $request) {
    if ($request->password === env('ADMIN_PASSWORD', 'admin123')) {
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_last_activity', time());
        return redirect()->route('admin.dashboard');
    }
    return back()->with('error', 'Mot de passe incorrect');
})->name('admin.login.post');

Route::post('/secureadmin/logout', function (Request $request) {
    $request->session()->forget(['admin_authenticated', 'admin_last_activity']);
    return redirect()->route('admin.login')->with('error', 'Vous avez été déconnecté');
})->name('admin.logout');

// Administration (protégé)
Route::prefix('secureadmin')->name('admin.')->middleware('admin.auth')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Ebooks
    Route::get('/ebooks', [AdminController::class, 'ebooks'])->name('ebooks');
    Route::get('/ebooks/create', [AdminController::class, 'ebooksCreate'])->name('ebooks.create');
    Route::post('/ebooks', [AdminController::class, 'ebooksStore'])->name('ebooks.store');
    Route::get('/ebooks/{id}/edit', [AdminController::class, 'ebooksEdit'])->name('ebooks.edit');
    Route::put('/ebooks/{id}', [AdminController::class, 'ebooksUpdate'])->name('ebooks.update');
    Route::delete('/ebooks/{id}', [AdminController::class, 'ebooksDestroy'])->name('ebooks.destroy');
    
    // Catégories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'categoriesCreate'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'categoriesStore'])->name('categories.store');
    Route::get('/categories/{id}/edit', [AdminController::class, 'categoriesEdit'])->name('categories.edit');
    Route::put('/categories/{id}', [AdminController::class, 'categoriesUpdate'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'categoriesDestroy'])->name('categories.destroy');
    
    // Commandes
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'ordersShow'])->name('orders.show');
    
    // Payment Settings
    Route::get('/payment-settings', [AdminController::class, 'paymentSettings'])->name('payment-settings');
    Route::post('/payment-settings', [AdminController::class, 'paymentSettingsUpdate'])->name('payment-settings.update');
});
