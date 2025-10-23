@extends('adminlte::page')
@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Editar Usuário</h2>
    <a href="/usuarios" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>
@endsection

@section('content')
<div class="card bg-light shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Informações do Usuário</h3>
    </div>

    <div class="card-body">
        <form action="#" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="Davi Queiroz">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="davi@example.com">
            </div>

            <div class="form-group">
                <label for="perfil">Perfil</label>
                <select id="perfil" name="perfil" class="form-control">
                    <option selected>Administrador</option>
                    <option>Gerente</option>
                    <option>Operacional</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option selected>Ativo</option>
                    <option>Inativo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="data_criacao">Data de Criação</label>
                <input type="text" id="data_criacao" name="data_criacao" class="form-control" value="2025-02-10" readonly>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="/usuarios" class="btn btn-danger ml-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
