<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\VendaController;

// Rotas de Autenticação para o painel de admin
Route::controller(AuthController::class)->group(function () {
    Route::get('admin/login', 'showLogin')->name('login')->middleware('guest');
    Route::post('admin/login', 'login')->middleware('guest');
    Route::post('admin/logout', 'logout')->name('logout')->middleware('auth');
});

// Rotas do Painel Administrativo
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas de CRUD para Produtos, Estoque e Vendas
    Route::resource('produtos', ProdutoController::class);
    Route::resource('estoque', EstoqueController::class);
    Route::resource('vendas', VendaController::class);
});

// Rota de fallback para redirecionar a raiz do admin para o dashboard
Route::get('/admin', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

