@extends('adminlte::page')

@section('title', 'Editar Veículo')

@section('content_header')
    <h2>Editar veículo</h2>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="#" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="marca">Marca</label>
                <input type="text" name="marca" id="marca" class="form-control" value="Toyota" required>
            </div>

            <div class="form-group">
                <label for="modelo">Modelo</label>
                <input type="text" name="modelo" id="modelo" class="form-control" value="Corolla XEi" required>
            </div>

            <div class="form-group">
                <label for="ano">Ano</label>
                <input type="number" name="ano" id="ano" class="form-control" value="2021" required>
            </div>

            <div class="form-group">
                <label for="cor">Cor</label>
                <input type="text" name="cor" id="cor" class="form-control" value="Prata" required>
            </div>

            <div class="form-group">
                <label for="preco">Preço</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">R$</span>
                    </div>
                    <input type="number" step="0.01" name="preco" id="preco" class="form-control" value="75000.00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="placa">Placa</label>
                <input type="text" name="placa" id="placa" class="form-control" value="ABC-1D23" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" rows="3" class="form-control">Veículo em ótimo estado, revisões em dia e único dono.</textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="/carros" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Atualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
