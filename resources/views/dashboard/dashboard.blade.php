@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')

@endsection

@section('content')
    @include('dashboard.partials.shortcuts')
    @include('dashboard.partials.charts')
@endsection


