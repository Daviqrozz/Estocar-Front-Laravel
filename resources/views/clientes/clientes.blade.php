@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Clientes cadastrados na plataforma</h2>
    <a href="/clientes/criar" class="btn btn-primary">
        <i class="fas fa-plus"></i> Cadastrar
    </a>
</div>
@endsection

@section('content')

<div class="card bg-light shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Lista de clientes</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
             
                    <th>Data de Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <a href="/clientes/editar/1" class="text-primary">
                            João Silv
                        </a>
                    </td>
                    <td>joao.silva@example.com</td>
                    <td>(21) 98877-6655</td>
                
                    <td>12/03/2024</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <a href="/clientes/editar/2" class="text-primary">
                            Maria Oliveira
                        </a>
                    </td>
                    <td>maria.oliveira@example.com</td>
                    <td>(21) 99123-4567</td>
              
                    <td>25/06/2024</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        <a href="/clientes/editar/3" class="text-primary">
                            Carlos Andrade
                        </a>
                    </td>
                    <td>carlos.andrade@example.com</td>
                    <td>(21) 99911-2233</td>
               
                    <td>08/01/2025</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
