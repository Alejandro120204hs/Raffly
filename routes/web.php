<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RifasController as AdminRifasController;
use App\Http\Controllers\Admin\UsuariosController as AdminUsuariosController;
use App\Http\Controllers\Admin\IngresosController as AdminIngresosController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboardController;
use App\Http\Controllers\Cliente\MisNumerosController as ClienteMisNumerosController;
use App\Http\Controllers\Cliente\RifasController as ClienteRifasController;
use App\Http\Controllers\Cliente\PerfilController as ClientePerfilController;
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

    Route::get('/usuarios', [AdminUsuariosController::class, 'index'])->name('usuarios.index');
    Route::get('/ingresos', [AdminIngresosController::class, 'index'])->name('ingresos.index');
});

// Rutas del Cliente
Route::prefix('cliente')->middleware(['auth', 'role:customer'])->name('cliente.')->group(function () {
    Route::get('/dashboard',    [ClienteDashboardController::class, 'index'])->name('dashboard');
    Route::get('/mis-numeros',  [ClienteMisNumerosController::class, 'index'])->name('mis-numeros');
    Route::get('/rifas',                              [ClienteRifasController::class, 'index'])->name('rifas');
    Route::get('/rifas/{id}',                         [ClienteRifasController::class, 'show'])->name('rifas.show');
    Route::post('/rifas/{id}/numeros/{numero}',        [ClienteRifasController::class, 'reservar'])->name('rifas.reservar');
    Route::get('/perfil',       [ClientePerfilController::class, 'index'])->name('perfil');
    Route::patch('/perfil',     [ClientePerfilController::class, 'update'])->name('perfil.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
