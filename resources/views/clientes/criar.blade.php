@extends('adminlte::page')

@section('title', 'Cadastrar Cliente')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Cadastrar Novo Cliente</h2>
    <a href="/clientes" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>
@endsection

@section('content')
<div class="card bg-light shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Informações do Cliente</h3>
    </div>

    <div class="card-body">
        <form action="#" method="POST">
            @csrf

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome completo">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@email.com">
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" placeholder="(00) 00000-0000">
            </div>

            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" class="form-control" placeholder="Rua, número, bairro, cidade">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option selected>Ativo</option>
                    <option>Inativo</option>
                    <option>Pendente</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Cadastrar Cliente
                </button>
                <a href="/clientes" class="btn btn-danger ml-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
