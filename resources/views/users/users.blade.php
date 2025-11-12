@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
@include('users.partials.users_header')
@endsection

@section('content')
@include('users.partials.users_dataframe')
@endsection
