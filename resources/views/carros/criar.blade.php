@extends('adminlte::page')

@section('title', 'Cadastrar Veículo')

@section('content_header')
    <h2>Cadastrar novo veículo</h2>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="#" method="POST">
            @csrf

            <div class="form-group">
                <label for="marca">Marca</label>
                <input type="text" name="marca" id="marca" class="form-control" placeholder="Ex: Toyota" required>
            </div>

            <div class="form-group">
                <label for="modelo">Modelo</label>
                <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ex: Corolla" required>
            </div>

            <div class="form-group">
                <label for="ano">Ano</label>
                <input type="number" name="ano" id="ano" class="form-control" placeholder="Ex: 2023" required>
            </div>

            <div class="form-group">
                <label for="cor">Cor</label>
                <input type="text" name="cor" id="cor" class="form-control" placeholder="Ex: Prata" required>
            </div>

            <div class="form-group">
                <label for="preco">Preço</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">R$</span>
                    </div>
                    <input type="number" step="0.01" name="preco" id="preco" class="form-control" placeholder="Ex: 75000" required>
                </div>
            </div>

            <div class="form-group">
                <label for="placa">Placa</label>
                <input type="text" name="placa" id="placa" class="form-control" placeholder="Ex: ABC-1234" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" rows="3" class="form-control" placeholder="Detalhes do veículo"></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="/carros" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
