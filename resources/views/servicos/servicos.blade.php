@extends('adminlte::page')

@section('title','Ordem de Servicos')

@include('layouts.token_check')

@section('content_header')

@include('servicos.partials.servicos_card')

@endsection

@section('content')

@include('servicos.partials.servicos_new')

@include('servicos.partials.servicos_dataframe')

@endsection