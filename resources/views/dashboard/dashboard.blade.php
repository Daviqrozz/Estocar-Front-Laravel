@extends('adminlte::page')

@php
    $adminlte = app('JeroenNoten\LaravelAdminLte\AdminLte');
    Auth::loginUsingId(1); //DEBUG:apenas para testar o menu do usuário
@endphp
 
@section('title', 'Dashboard')

@section('content_header')
    <h1>Bem-vindo, {{Auth::user()->name}}</h1>
@endsection

@section('content')
    @include('dashboard.partials.shortcuts')
@endsection

@section('content')
@endsection
