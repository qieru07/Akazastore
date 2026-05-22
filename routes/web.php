<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\PaymentMethodController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/game/{slug}', [GameController::class, 'show'])->name('game.show');
    Route::get('/dashboard', function () {
        $riwayat_transaksi = \App\Models\Transaction::orderBy('id', 'desc')->limit(10)->get();
        return view('dashboard', compact('riwayat_transaksi'));
        })->middleware(['auth', 'verified'])->name('dashboard');
        
        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            });
            
Route::resource('banners', BannerController::class);
Route::resource('games', GameController::class);
Route::resource('products', ProductController::class);
Route::resource('payment-methods', PaymentMethodController::class);
Route::resource('transactions', TransactionController::class);
Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
Route::post('transactions/clear-completed', [TransactionController::class, 'clearCompleted'])->name('transactions.clearCompleted');

require __DIR__.'/auth.php';
