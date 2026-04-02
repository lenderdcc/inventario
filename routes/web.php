<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompraController;

/*
|--------------------------------------------------------------------------
| Web Routes - MIC Tecnología
|--------------------------------------------------------------------------
*/

// --- PÁGINA PRINCIPAL (CATÁLOGO) ---
Route::get('/', [TiendaController::class, 'index'])->name('inicio');

// --- AUTENTICACIÓN ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// --- RUTAS PROTEGIDAS (REQUIEREN LOGIN) ---
Route::middleware(['auth'])->group(function () {

    // Agrega esto dentro del bloque Route::middleware(['auth'])->group(function () { ... });

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Dashboard General
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- GESTIÓN DE PRODUCTOS ---
    // Esta es la ruta para el botón de sumar stock que acabamos de crear
    Route::post('/productos/update-stock/{id}', [ProductoController::class, 'updateStock'])->name('productos.updateStock');

    // CRUD completo de productos (Index, Create, Store, Edit, Update, Destroy)
    Route::resource('productos', ProductoController::class);

    // Procesos de Compra
    Route::get('/checkout', [CompraController::class, 'checkout'])->name('checkout');
    Route::post('/procesar-compra', [CompraController::class, 'procesarCompra'])->name('compra.procesar');

    // Notificaciones
    Route::post('/notificaciones/leer', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notificaciones.leer');
});

// PANEL DE ADMINISTRACIÓN (Separado por si escalas el proyecto)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// --- CARRITO DE COMPRAS (DISPONIBLE PARA TODOS) ---
Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::get('/carrito/limpiar', [CarritoController::class, 'limpiar'])->name('carrito.limpiar');
