@extends('adminlte::page')

@section('title', 'Relatórios de Vendas')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Relatórios de Vendas</h2>
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
                <label for="cliente">Cliente</label>
                <input type="text" id="cliente" name="cliente" class="form-control" placeholder="Nome do cliente">
            </div>

            <div class="form-group col-md-3">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="concluida">Concluída</option>
                    <option value="pendente">Pendente</option>
                    <option value="cancelada">Cancelada</option>
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
                    <th>Cliente</th>
                    <th>Carro</th>
                    <th>Data da Venda</th>
                    <th>Valor (R$)</th>
                    <th>Usuario</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>João Silva</td>
                    <td>Fiat Argo</td>
                    <td>10/10/2025</td>
                    <td>58.900,00</td>
                    <td>{{Auth::user()->name}}</td>
                    <td><span class="badge bg-success">Concluída</span></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
@endsection
