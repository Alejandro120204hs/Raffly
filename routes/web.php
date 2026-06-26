<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

// Ruta para el dashboard del admin
Route::get('/admin/dashboard', function () {
    return view('admin.admin-dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

// Ruta para el dashboard del cliente
Route::get('/cliente/dashboard', function () {
    return view('cliente.cliente-dashboard');
})->middleware(['auth', 'role:customer'])->name('cliente.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
