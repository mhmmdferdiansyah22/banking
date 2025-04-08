<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PocketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (!auth()->check()) {
        return view('auth.login');
    }

    if (Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'bank'){
        return redirect()->route('register');
    }

    if (Auth::user()->role->name == 'student') {
        return redirect()->route('wallet');
    }

    return abort(403, 'Unauthorized');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('users/{id}/edit', [RegisterController::class, 'edit'])->name('users.edit');
Route::put('users/{id}', [RegisterController::class, 'update'])->name('users.update');
Route::post('/register', [RegisterController::class, 'register']);
Route::delete('/users/{id}', [RegisterController::class, 'destroy'])->name('users.destroy');

Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/topup', [WalletController::class, 'topUp'])->name('wallet.topup');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
    Route::post('/wallet/update-status/{id}', [WalletController::class, 'updateStatus'])->name('wallet.updateStatus');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::post('/pocket/topup', [PocketController::class, 'topUp'])->name('pocket.topup');
    Route::post('/pocket/withdraw', [PocketController::class, 'withdraw'])->name('pocket.withdraw');
});
Route::get('/pocket', [PocketController::class, 'index'])->name('pocket');
Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
