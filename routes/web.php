<?php

use Illuminate\Support\Facades\Route;

Route::get('/login',function (){
    return view('auth/login');
});
Route::get('/register',function (){
    return view('auth/register');
});


Route::get('/', function () {
    return view('dashboard/dashboard');
});

Route::get('/carros', function (){
    return view('carros/carros');
});