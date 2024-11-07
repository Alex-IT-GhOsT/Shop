<?php

use App\Http\Controllers\BascketController;
use App\Http\Controllers\FilterProduct;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/', [FilterProduct::class, 'index']);
Route::get('/api/products/',[FilterProduct::class, 'apiIndex'] );


Route::get('/my-backet', [BascketController::class, 'index'] );
Route::get('/my-order', [OrderController::class, 'index']);


require __DIR__.'/auth.php';
