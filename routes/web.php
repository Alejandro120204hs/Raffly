<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RifasController as AdminRifasController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

// Rutas del Administrador
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/rifas', [AdminRifasController::class, 'index'])->name('rifas.index');
    Route::get('/rifas/crear', [AdminRifasController::class, 'create'])->name('rifas.create');
    Route::post('/rifas', [AdminRifasController::class, 'store'])->name('rifas.store');
    Route::get('/rifas/{id}', [AdminRifasController::class, 'show'])->name('rifas.show');
    Route::patch('/rifas/{id}/resultado', [AdminRifasController::class, 'registrarResultado'])->name('rifas.resultado');
    Route::get('/rifas/{id}/editar', [AdminRifasController::class, 'edit'])->name('rifas.edit');
    Route::patch('/rifas/{id}', [AdminRifasController::class, 'update'])->name('rifas.update');
    Route::patch('/rifas/{id}/finalizar', [AdminRifasController::class, 'finalizar'])->name('rifas.finalizar');
    Route::patch('/rifas/{rifa}/numeros/{numero}', [AdminRifasController::class, 'updateNumero'])->name('rifas.numeros.update');

});

// Rutas del Cliente
Route::get('/cliente/dashboard', function () {
    return view('cliente.cliente-dashboard');
})->middleware(['auth', 'role:customer'])->name('cliente.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
