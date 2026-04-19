<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Only for logged-in users)
Route::middleware(['auth'])->group(function () {
    // Dashboard and Transactions
    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Settings Functionality (Profile & Password Updates)
    Route::put('/profile/update', [TransactionController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [TransactionController::class, 'updatePassword'])->name('password.update');

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
