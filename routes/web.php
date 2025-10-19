<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::get('/',function () {
    return redirect('/home');
});
Route::get('/carros',function () {
    return view('carros/carros');
});

Route::get('/carros/criar', function (){
    return view('carros/criar');
})->name('criar_carro');

Route::get('/carros/editar/{carro}', function (){
    return view('carros/editar');
})->name('editar_carro');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');


