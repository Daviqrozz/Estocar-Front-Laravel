@extends('adminlte::page')

@section('title', 'Carros')

@section('content_header')
    @include('carros.partials.carros_header')
@endsection

@section('content')
    @include('carros.partials.carros_dataframe')
@endsection



