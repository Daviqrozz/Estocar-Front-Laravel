@extends('adminlte::page')

@section('title', 'Carros')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Carros cadastrados na plataforma</h2>
    <a href="#" class="btn btn-primary">
        <i class="fas fa-plus"></i> Cadastrar
    </a>
</div>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de veículos</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca / Modelo</th>
                    <th>Ano</th>
                    <th>Placa</th>
                    <th>Status</th>
                    <th>Valor Fipe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <a href="/carros/editar/1" class="text-primary">
                            Toyota Corolla XEi
                        </a>
                    </td>
                    <td>2021</td>
                    <td>ABC-1D23</td>
                    <td>
                        <select class="status-select form-control form-control-sm text-white font-weight-bold">
                            <option value="disponivel" selected>Disponível</option>
                            <option value="manutencao">Em manutenção</option>
                            <option value="vendido">Vendido</option>
                        </select>
                    </td>
                    <td>R$ 75.000,00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <a href="/carros/editar/2" class="text-primary">
                            Honda Civic Touring
                        </a>
                    </td>
                    <td>2020</td>
                    <td>XYZ-9F87</td>
                    <td>
                        <select class="status-select form-control form-control-sm text-white font-weight-bold">
                            <option value="disponivel">Disponível</option>
                            <option value="manutencao" selected>Em manutenção</option>
                            <option value="vendido">Vendido</option>
                        </select>
                    </td>
                    <td>R$ 82.500,00</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        <a href="/carros/editar/3" class="text-primary">
                            Volkswagen Polo MSI
                        </a>
                    </td>
                    <td>2019</td>
                    <td>JKL-3E45</td>
                    <td>
                        <select class="status-select form-control form-control-sm text-white font-weight-bold">
                            <option value="disponivel">Disponível</option>
                            <option value="manutencao">Em manutenção</option>
                            <option value="vendido" selected>Vendido</option>
                        </select>
                    </td>
                    <td>R$ 58.200,00</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="m-2">
        <label class="mr-1" for="select_count">Mostrar:</label>
        <select name="count" id="select_count">
            <option value="10">10</option>
            <option value="10">50</option>
            <option value="10">100</option>
        </select>
    </div>
</div>

{{-- Script que muda a cor automaticamente --}}
@section('js')
<script>
    function updateSelectColor(select) {
        const value = select.value;
        select.style.color = '#fff';
        select.style.borderColor = 'transparent';

        switch (value) {
            case 'disponivel':
                select.style.backgroundColor = '#28a745'; // Verde
                break;
            case 'manutencao':
                select.style.backgroundColor = '#ffc107'; // Amarelo
                break;
            case 'vendido':
                select.style.backgroundColor = '#6c757d'; // Cinza
                break;
            default:
                select.style.backgroundColor = '#007bff';
        }
    }

    // Aplica ao carregar
    document.querySelectorAll('.status-select').forEach(select => {
        updateSelectColor(select);
        select.addEventListener('change', () => updateSelectColor(select));
    });
</script>
@endsection

@endsection
