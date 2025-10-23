@extends('adminlte::page')

@section('title', 'Cadastrar Usuário')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Cadastrar Novo Usuário</h2>
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

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome completo">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@email.com">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite uma senha segura">
            </div>

            <div class="form-group">
                <label for="cargo">Cargo / Função</label>
                <input type="text" id="cargo" name="cargo" class="form-control" placeholder="Ex: Administrador, Vendedor, Técnico...">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option selected>Ativo</option>
                    <option>Inativo</option>
                    <option>Suspenso</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Cadastrar Usuário
                </button>
                <a href="/usuarios" class="btn btn-danger ml-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
