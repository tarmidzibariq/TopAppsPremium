<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
// use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// | Web Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/', [AuthenticatedSessionController::class, 'store']);
});

Route::redirect('/login', '/');

Route::middleware(['auth'])->group(function () {
    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // // order
    // Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    // Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');

    // service
    Route::resource('/service', ServiceController::class)->names('service');

    // category
    Route::resource('/category', CategoryController::class)->names('category');

    // user
    Route::resource('/users', UserController::class)->names('users');
    
    // report
    Route::get('/report', [ ReportController::class, 'index'])->name('report.index');

    Route::get('/report/print', [ReportController::class, 'print'])->name('report.print');
    Route::get('/report/export', [ReportController::class, 'export'])->name('report.export');
});

require __DIR__.'/auth.php';
