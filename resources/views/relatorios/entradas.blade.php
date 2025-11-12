@extends('adminlte::page')

@section('title', 'Relatórios de Entradas')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Relatório de Entradas (Carros Cadastrados)</h2>
    <a href="/home" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Filtros de Relatório</h3>
    </div>
    <div class="card-body">
        <form action="#" method="GET" class="row">
            <div class="form-group col-md-3">
                <label for="data_inicial">Data Inicial</label>
                <input type="date" id="data_inicial" name="data_inicial" class="form-control">
            </div>

            <div class="form-group col-md-3">
                <label for="data_final">Data Final</label>
                <input type="date" id="data_final" name="data_final" class="form-control">
            </div>

            <div class="form-group col-md-3">
                <label for="marca">Marca</label>
                <input type="text" id="marca" name="marca" class="form-control" placeholder="Ex: Chevrolet, Fiat...">
            </div>

            <div class="form-group col-md-3">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="disponivel">Disponível</option>
                    <option value="vendido">Vendido</option>
                    <option value="manutencao">Manutenção</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Gerar Relatório
                </button>
                <button type="reset" class="btn btn-secondary ml-2">
                    <i class="fas fa-eraser"></i> Limpar Filtros
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-dark text-white">
        <h3 class="card-title">Resultados</h3>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Marca / Modelo</th>
                    <th>Ano</th>
                    <th>Cor</th>
                    <th>Placa</th>
                    <th>Valor (R$)</th>
                    <th>Data de Cadastro</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Fiat Argo</td>
                    <td>2022</td>
                    <td>Branco</td>
                    <td>ABC-1234</td>
                    <td>58.900,00</td>
                    <td>05/10/2025</td>
                    <td><span class="badge bg-success">Disponível</span></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
@endsection
