<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

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

// Paiement
Route::get('/commander', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commander/traiter', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/commande/confirmation/{numeroCommande}', [CheckoutController::class, 'success'])->name('checkout.success');
