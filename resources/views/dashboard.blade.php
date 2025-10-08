@extends('adminlte::page')

@php
    $adminlte = app('JeroenNoten\LaravelAdminLte\AdminLte');
    Auth::loginUsingId(1); // apenas para testar o menu do usuário
@endphp

@section('title', 'Dashboard')

@section('content_header')
    <h1>Bem-vindo ao Estocar</h1>
@endsection

@section('content')
    <p>Conteúdo principal aqui.</p>
@endsection
