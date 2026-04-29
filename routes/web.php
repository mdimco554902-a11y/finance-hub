<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController; 
use App\Http\Controllers\SavingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard / Main Overview
    Route::get('/', [TransactionController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [TransactionController::class, 'index']); // Alternative URL
    
    // Transactions
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Budgets
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::put('/budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    // Savings Goals
    Route::post('/savings', [SavingController::class, 'store'])->name('savings.store');
    Route::post('/savings/contribute', [SavingController::class, 'contribute'])->name('savings.contribute');
    Route::put('/savings/{saving}', [SavingController::class, 'update'])->name('savings.update');
    Route::delete('/savings/{saving}', [SavingController::class, 'destroy'])->name('savings.destroy');

    // Account & Profile Settings
    Route::put('/profile/update', [TransactionController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [TransactionController::class, 'updatePassword'])->name('password.update');

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});