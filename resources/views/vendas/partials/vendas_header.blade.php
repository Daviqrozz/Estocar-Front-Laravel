@extends('adminlte::page')

@section('title', 'Vendas - Histórico')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Vendas Registradas na Plataforma</h2>
    <a href="/vendas/criar" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Registrar Nova Venda
    </a>
</div>
@endsection

@section('content')
<div class="card bg-dark shadow-sm">
    <div class="card-header border-0">
        <h3 class="card-title text-white">Lista de Transações</h3>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover dataTable" id="tabela_vendas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Carro Vendido</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Data</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>00125</td>
                        <td>Chevrolet Onix (2021)</td>
                        <td>Maria Souza</td>
                        <td>Davi Rodrigues</td>
                        <td>10/11/2025</td>
                        <td>R$ 55.000,00</td>
                        <td>
                            <span class="badge badge-success" style="font-size: 0.9em;">Paga</span>
                        </td>
                        <td class="text-center">
                            <a href="/vendas/editar/125" class="btn btn-sm btn-info" title="Editar"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-danger" title="Cancelar Venda"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>00124</td>
                        <td>Ford Ranger (2023)</td>
                        <td>João Silva</td>
                        <td>Davi Rodrigues</td>
                        <td>05/11/2025</td>
                        <td>R$ 72.500,00</td>
                        <td>
                            <span class="badge badge-warning" style="font-size: 0.9em;">Pendente</span>
                        </td>
                        <td class="text-center">
                            <a href="/vendas/editar/124" class="btn btn-sm btn-info" title="Editar"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-danger" title="Cancelar Venda"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>00123</td>
                        <td>Toyota Yaris (2020)</td>
                        <td>Pedro Alves</td>
                        <td>Davi Rodrigues</td>
                        <td>01/11/2025</td>
                        <td>R$ 173.484,70</td>
                        <td>
                            <span class="badge badge-danger" style="font-size: 0.9em;">Cancelada</span>
                        </td>
                        <td class="text-center">
                            <a href="/vendas/editar/123" class="btn btn-sm btn-info" title="Detalhes"><i class="fas fa-eye"></i></a>
                            <button class="btn btn-sm btn-secondary" disabled title="Cancelada"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection

{{-- -------------------------------------------------------------------------------- --}}

@push('js')
<script>

</script>
@endpush