<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Quick Switch Role (for demonstration and convenience in Pertemuan 5)
Route::get('/switch-role/{role}', function ($role) {
    if (in_array($role, ['admin', 'staff'])) {
        $user = \App\Models\User::where('role', $role)->first();
        if ($user) {
            \Illuminate\Support\Facades\Auth::login($user);
            return back()->with('success', "Beralih role menjadi {$user->name} ({$user->role})");
        }
    }
    return back();
})->name('switch.role');

// Application Routes
Route::group([], function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products Resource Controller (index, create, store, show, edit, update, destroy)
    Route::resource('products', ProductController::class);

    // Stock Management Routes
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/in', [StockController::class, 'createIn'])->name('in');
        Route::post('/in', [StockController::class, 'storeIn'])->name('in.store');
        Route::get('/out', [StockController::class, 'createOut'])->name('out');
        Route::post('/out', [StockController::class, 'storeOut'])->name('out.store');
        Route::get('/history', [StockController::class, 'history'])->name('history');
        Route::get('/print', [StockController::class, 'printReport'])->name('print');
    });
});
