@extends('adminlte::page')


@section('title', 'Dashboard')



@section('content_header')
<h1>Dashboard</h1>
@endsection

@section('content')
    @include('dashboard.partials.shortcuts')
    @include('dashboard.partials.charts')
    
    {{-- Verificação do token --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('api_token');
            
            if (!token) {
                   window.location.href = '{{ route('login') }}'; 
            }
        });
    </script>
@endsection
