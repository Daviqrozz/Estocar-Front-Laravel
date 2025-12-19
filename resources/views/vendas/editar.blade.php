@extends('adminlte::page')

@section('title', 'Editar Venda')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h2>Editar Venda - <span id="venda-id">{{ $vendaID }}</span></h2>

        <div class="" id="delete-button-container">
            <form id="delete_venda_form">
                <button type="submit" id="delete_venda_btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Excluir Venda
                </button>
            </form>
        </div>
    </div>
@stop

@section('content')
    @include('layouts.token_check')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dados da Venda</h3>
        </div>
        <div class="card-body" id="venda-form-container">

            <div class="text-center" id="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x"></i> Carregando dados da venda...
            </div>

            <form id="edit_venda_form" style="display:none;">
                <div class="row">
                    <!-- CAMPOS SOMENTE LEITURA -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-car text-primary"></i> Carro Vendido</label>
                            <input type="text" id="carro_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-user text-primary"></i> Cliente</label>
                            <input type="text" id="cliente_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-user-tie text-primary"></i> Vendedor</label>
                            <input type="text" id="vendedor_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-calendar text-primary"></i> Data da Venda</label>
                            <input type="text" id="data_venda_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <!-- CAMPOS EDITÁVEIS -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valor_venda"><i class="fas fa-dollar-sign text-success"></i> Valor da Venda (R$)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" step="0.01" name="valor_venda" id="valor_venda" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status"><i class="fas fa-info-circle text-warning"></i> Status</label>
                            <select id="status" name="status" class="status-select form-control form-control-sm text-white font-weight-bold">
                                <option value="0">Pendente</option>
                                <option value="1">Paga</option>
                                <option value="2">Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
                </div>
            </form>

            <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>
        </div>
    </div>
@stop

@push('js')
<script>
    const VENDA_ID = '{{ $vendaID }}';
    const API_URL = 'http://estocar-1.test/api';
    const VENDA_FETCH_ENDPOINT = `/lista/vendas/${VENDA_ID}`;
    const VENDA_UPDATE_ENDPOINT = `/editar/venda/${VENDA_ID}`;
    const VENDA_DELETE_ENDPOINT = `/deletar/venda/${VENDA_ID}`;

    async function apiFetch(endpoint, options = {}) {
        const token = localStorage.getItem('api_token');
        const fetchUrl = endpoint.startsWith(API_URL) ? endpoint : `${API_URL}${endpoint}`;

        if (!token) {
            console.error("Token de API ausente.");
            return Promise.reject(new Error("Token de autenticação ausente."));
        }

        const defaultHeaders = {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            ...(options.method === 'POST' || options.method === 'PUT' || options.method === 'PATCH' ? {
                'Content-Type': 'application/json'
            } : {}),
        };

        const response = await fetch(fetchUrl, {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {}),
            },
            body: typeof options.body === 'object' && defaultHeaders['Content-Type'] ? JSON.stringify(options.body) : options.body,
        });

        if (response.status === 401 || response.status === 403) {
            localStorage.removeItem('api_token');
            window.location.href = '{{ route('login') }}';
            return Promise.reject(new Error("Não autorizado."));
        }

        return response;
    }

    // Carrega dados da venda
    async function loadVendaData() {
        const formContainer = document.getElementById('venda-form-container');
        const loadingSpinner = document.getElementById('loading-spinner');
        const form = document.getElementById('edit_venda_form');
        const errorBox = document.getElementById('error-message');

        loadingSpinner.style.display = 'block';
        form.style.display = 'none';
        errorBox.style.display = 'none';

        try {
            const response = await apiFetch(VENDA_FETCH_ENDPOINT, { method: 'GET' });

            if (response.ok) {
                const data = await response.json();
                const venda = data.venda;

                // Preenche campos LEITURA
                document.getElementById('carro_info').value = `${venda.carro?.marca || 'N/A'} ${venda.carro?.modelo || ''}`;
                document.getElementById('cliente_info').value = venda.cliente?.nome || 'N/A';
                document.getElementById('vendedor_info').value = venda.user?.name || '—';
                document.getElementById('data_venda_info').value = new Date(venda.data_venda).toLocaleDateString('pt-BR');

                // Preenche campos EDITÁVEIS
                document.getElementById('valor_venda').value = parseFloat(venda.valor_venda || 0);
                document.getElementById('status').value = venda.status || 0;

                document.getElementById('venda-id').textContent = venda.id;

                loadingSpinner.style.display = 'none';
                form.style.display = 'block';
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || `Erro ao buscar venda: ${response.statusText}`);
            }
        } catch (error) {
            console.error("Falha ao carregar os dados:", error);
            loadingSpinner.style.display = 'none';
            errorBox.innerText = `Erro ao carregar a venda ID ${VENDA_ID}: ${error.message}`;
            errorBox.style.display = 'block';
        }
    }

    // Salvar alterações
    async function handleEditFormSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('edit_venda_form');
        const submitButton = form.querySelector('button[type="submit"]');
        const errorBox = document.getElementById('error-message');

        errorBox.style.display = 'none';
        submitButton.disabled = true;
        submitButton.innerText = 'Salvando...';

        const updateData = {
            valor_venda: parseFloat(form.elements['valor_venda'].value),
            status: parseInt(form.elements['status'].value)
        };

        try {
            const response = await apiFetch(VENDA_UPDATE_ENDPOINT, {
                method: 'PUT',
                body: updateData
            });

            if (response.ok) {
                window.location.href = '{{ route('vendas.vendas') }}';
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Erro desconhecido ao salvar.');
            }
        } catch (error) {
            console.error("Falha ao salvar:", error);
            errorBox.innerText = `Erro ao salvar: ${error.message}`;
            errorBox.style.display = 'block';
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Salvar Alterações';
        }
    }

    // Deletar venda
    async function handleDeleteFormSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('delete_venda_form');
        const submitButton = form.querySelector('button[type="submit"]');
        const errorBox = document.getElementById('error-message');

        errorBox.style.display = 'none';
        submitButton.disabled = true;
        submitButton.innerText = 'Deletando...';

        try {
            const response = await apiFetch(VENDA_DELETE_ENDPOINT, {
                method: 'DELETE'
            });

            if (response.ok) {
                window.location.href = '{{ route('vendas.vendas') }}';
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Erro desconhecido ao deletar.');
            }
        } catch (error) {
            console.error("Falha ao deletar:", error);
            errorBox.innerText = `Erro ao deletar: ${error.message}`;
            errorBox.style.display = 'block';
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Excluir Venda';
        }
    }

    // Inicialização
    document.addEventListener('DOMContentLoaded', () => {
        if (VENDA_ID && VENDA_ID !== 'ID_Placeholder') {
            loadVendaData();
            document.getElementById('edit_venda_form').addEventListener('submit', handleEditFormSubmit);
            document.getElementById('delete_venda_form').addEventListener('submit', handleDeleteFormSubmit);
        } else {
            document.getElementById('venda-form-container').innerHTML =
                '<div class="alert alert-danger">ID da venda inválido.</div>';
        }
    });
</script>
@endpush
