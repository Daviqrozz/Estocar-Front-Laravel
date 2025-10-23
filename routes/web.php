<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// Redireciona para /home ao acessar a raiz
Route::get('/', function () {
    return redirect('/home');
});

// Dashboard principal
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rotas de Carros
|--------------------------------------------------------------------------
*/

Route::prefix('carros')->group(function () {
    Route::get('/', function () {
        return view('carros.carros');
    })->name('carros.index');

    Route::get('/criar', function () {
        return view('carros.criar');
    })->name('carros.criar');

    Route::get('/editar/{carro}', function () {
        return view('carros.editar');
    })->name('carros.editar');
});

/*
|--------------------------------------------------------------------------
| Rotas de Usuários
|--------------------------------------------------------------------------
*/

Route::prefix('usuarios')->group(function () {
    Route::get('/', function () {
        return view('users.users');
    })->name('usuarios.users');

    Route::get('/criar', function () {
        return view('users.criar');
    })->name('users.criar');

    Route::get('/editar/{usuario}', function () {
        return view('users.editar');
    })->name('users.editar');
});

/*
|--------------------------------------------------------------------------
| Rotas de Clientes
|--------------------------------------------------------------------------
*/

Route::prefix('clientes')->group(function () {
    Route::get('/', function () {
        return view('clientes.clientes');
    })->name('clientes.clientes');

    Route::get('/criar', function () {
        return view('clientes.criar');
    })->name('clientes.criar');

    Route::get('/editar/{cliente}', function () {
        return view('clientes.editar');
    })->name('clientes.editar');
});

/*
|--------------------------------------------------------------------------
| Rotas de Relatórios
|--------------------------------------------------------------------------
*/

Route::prefix('relatorios')->group(function () {
    Route::get('/', function () {
        return view('relatorios.index');
    })->name('relatorios.index');
});
