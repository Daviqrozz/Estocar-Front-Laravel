@extends('adminlte::page')

@section('title', 'Vendas')
@include('layouts.token_check')
@section('content_header')

@include('vendas.partials.vendas_header')
@endsection

@section('content')
@include('vendas.partials.vendas_dataframe')
@endsection
