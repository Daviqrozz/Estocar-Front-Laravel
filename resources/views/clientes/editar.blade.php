@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Editar Cliente</h2>
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
            @method('PUT')

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="João Silva">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="joao.silva@example.com">
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="(21) 98877-6655">
            </div>

            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" class="form-control" value="Rua das Flores, 123 - Centro, Rio de Janeiro">
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
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="/clientes" class="btn btn-danger ml-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
