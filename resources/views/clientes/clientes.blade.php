@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    @include('clientes.partials.clientes_header')
@endsection

@section('content')
    @include('clientes.partials.clientes_dataframe')
@endsection
