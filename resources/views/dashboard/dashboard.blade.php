@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1>Bem-vindo, {{Auth::user()->name}}</h1>
@endsection

@section('content')
    @include('dashboard.partials.shortcuts')
    @include('dashboard.partials.charts')
@endsection


