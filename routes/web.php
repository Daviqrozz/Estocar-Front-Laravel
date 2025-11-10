<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\CarroViewController;
use App\Http\Controllers\ClienteViewController;

// Redireciona a raiz para /home
Route::get('/', function () {
    return redirect('/home');
});

// Rota de autenticação:
Route::get('login', [ViewController::class, 'render'])->name('login')->defaults('viewName', 'auth.login');
Route::get('logout', [ViewController::class, 'render'])->name('logout')->defaults('viewName', 'auth.logout');
Route::get('register', [ViewController::class, 'render'])->name('register')->defaults('viewName', 'auth.register');

// Dashboard principal
Route::get('/home', [ViewController::class, 'render'])->name('home')->defaults('viewName', 'dashboard.dashboard');


Route::prefix('carros')->group(function () {
    // Nome da view: carros.carros
    Route::get('/', [ViewController::class, 'render'])->name('carros.carros')->defaults('viewName', 'carros.carros');
    
    // Nome da view: carros.criar
    Route::get('/criar', [ViewController::class, 'render'])->name('carros.criar')->defaults('viewName', 'carros.criar');
    
    // Nome da view: carros.editar
    Route::get('/editar/{carro}', [CarroViewController::class, 'update'])->name('carros.editar');
});


Route::prefix('usuarios')->group(function () {
    // Nome da view: users.users
    Route::get('/', [ViewController::class, 'render'])->name('usuarios.users')->defaults('viewName', 'users.users');

    // Nome da view: users.criar
    Route::get('/criar', [ViewController::class, 'render'])->name('users.criar')->defaults('viewName', 'users.criar');

    // Nome da view: users.editar
    Route::get('/editar/{usuario}', [ViewController::class, 'render'])->name('users.editar')->defaults('viewName', 'users.editar');
});


Route::prefix('clientes')->group(function () {
    // Nome da view: clientes.clientes
    Route::get('/', [ViewController::class, 'render'])->name('clientes.clientes')->defaults('viewName', 'clientes.clientes');

    // Nome da view: clientes.criar
    Route::get('/criar', [ViewController::class, 'render'])->name('clientes.criar')->defaults('viewName', 'clientes.criar');

    // Nome da view: clientes.editar
    Route::get('/editar/{cliente}', [ClienteViewController::class, 'update'])->name('clientes.editar');
});


Route::prefix('relatorios')->group(function () {
    // Nome da view: relatorios.vendas
    Route::get('/vendas', [ViewController::class, 'render'])->name('relatorios.vendas')->defaults('viewName', 'relatorios.vendas');
    
    // Nome da view: relatorios.entradas
    Route::get('/entradas', [ViewController::class, 'render'])->name('relatorios.entradas')->defaults('viewName', 'relatorios.entradas');
});