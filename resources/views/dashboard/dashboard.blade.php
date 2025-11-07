@extends('adminlte::page')


@section('title', 'Dashboard')
@include('layouts.token_check')


@section('content_header')
<h1>Dashboard</h1>
@endsection

@section('content')
    @include('dashboard.partials.shortcuts')
    @include('dashboard.partials.charts')
@endsection
