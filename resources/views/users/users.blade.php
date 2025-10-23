@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Usuários cadastrados na plataforma</h2>
    <a href="#" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Cadastrar
    </a>
</div>
@endsection

@section('content')
<div class="card bg-light shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Lista de usuários</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Grupo</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <a href="/usuarios/editar/1" class="text-primary">
                            Davi Queiroz
                        </a>
                    </td>
                    <td>davi@example.com</td>
                    <td>Administrador</td>
                    <td>2025-02-10</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <a href="/usuarios/editar/2" class="text-primary">
                            Pedro Ribeiro
                        </a>
                    </td>
                    <td>pedro@example.com</td>
                    <td>Gerente</td>
                    <td>2025-03-15</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        <a href="/usuarios/editar/3" class="text-primary">
                            Maria Silva
                        </a>
                    </td>
                    <td>maria@example.com</td>
                    <td>Operacional</td>
                    <td>2025-04-01</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
