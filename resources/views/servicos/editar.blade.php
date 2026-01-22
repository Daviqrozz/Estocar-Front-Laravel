@extends('adminlte::page')

@section('title', 'Editar Ordem de Serviço')
@include('layouts.token_check')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h2>Editar Ordem de Serviço - <span id="os-id">#{{ $servicoID }}</span></h2>

        <div id="delete-button-container">
            <form id="delete_os_form">
                <button type="submit" id="delete_os_btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Excluir OS
                </button>
            </form>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title">Dados da Ordem de Serviço</h3>
        </div>
        <div class="card-body" id="os-form-container">

            <div class="text-center" id="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x"></i> Carregando dados da OS...
            </div>

            <form id="edit_os_form" style="display:none;">
                <div class="row">
                    <!-- CAMPOS SOMENTE LEITURA -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-user text-primary"></i> Cliente</label>
                            <input type="text" id="cliente_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-car text-primary"></i> Carro</label>
                            <input type="text" id="carro_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-wrench text-primary"></i> Serviço</label>
                            <input type="text" id="servico_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-calendar text-primary"></i> Data de Abertura</label>
                            <input type="text" id="data_abertura_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>

                    <!-- CAMPOS EDITÁVEIS -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valor_total"><i class="fas fa-dollar-sign text-success"></i> Valor Total (R$)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" step="0.01" name="valor_total" id="valor_total" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status"><i class="fas fa-info-circle text-warning"></i> Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="0">Aberta</option>
                                <option value="1">Em andamento</option>
                                <option value="2">Concluída</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descricao"><i class="fas fa-file-alt text-info"></i> Observações</label>
                            <textarea name="descricao" id="descricao" class="form-control" rows="4" placeholder="Descrição do serviço..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>

            <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>
        </div>
    </div>
@stop

@push('js')
<script>
    const OS_ID = '{{ $servicoID }}';
    const API_URL = 'http://estocar-1.test/api';
    const OS_FETCH_ENDPOINT = `/lista/ordens-servico/${OS_ID}`;
    const OS_UPDATE_ENDPOINT = `/editar/ordem-servico/${OS_ID}`;
    const OS_DELETE_ENDPOINT = `/deletar/ordem-servico/${OS_ID}`;

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
        };

        const response = await fetch(fetchUrl, {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {}),
            },
            body: options.body
        });

        if (response.status === 401 || response.status === 403) {
            localStorage.removeItem('api_token');
            window.location.href = '{{ route('login') }}';
            return Promise.reject(new Error("Não autorizado."));
        }

        return response;
    }

    function mapStatus(status) {
        if (status === 0) return 'Aberta';
        if (status === 1) return 'Em andamento';
        if (status === 2) return 'Concluída';
        return 'Desconhecido';
    }

    // Carrega dados da OS
    async function loadOsData() {
        const loadingSpinner = document.getElementById('loading-spinner');
        const form = document.getElementById('edit_os_form');
        const errorBox = document.getElementById('error-message');

        loadingSpinner.style.display = 'block';
        form.style.display = 'none';
        errorBox.style.display = 'none';

        try {
            const response = await apiFetch(OS_FETCH_ENDPOINT, { method: 'GET' });

            if (response.ok) {
                const data = await response.json();
                const os = data.ordem_servico || data;

                // Preenche campos SOMENTE LEITURA
                document.getElementById('cliente_info').value = os.cliente?.nome || 'N/A';
                
                const carroTexto = os.carro 
                    ? `${os.carro.marca || ''} ${os.carro.modelo || ''} ${os.carro.ano || ''}`.trim()
                    : 'N/A';
                document.getElementById('carro_info').value = carroTexto;

                // Pega nome do serviço dos registros
                let servicoNome = 'N/A';
                if (Array.isArray(os.registros) && os.registros.length > 0) {
                    const reg = os.registros[0];
                    if (reg.servico && reg.servico.nome) {
                        servicoNome = reg.servico.nome;
                    }
                }
                document.getElementById('servico_info').value = servicoNome;

                const dataFormatada = os.data_abertura 
                    ? new Date(os.data_abertura).toLocaleDateString('pt-BR')
                    : '-';
                document.getElementById('data_abertura_info').value = dataFormatada;

                // Preenche campos EDITÁVEIS
                document.getElementById('valor_total').value = parseFloat(os.valor_total || 0);
                document.getElementById('status').value = os.status || 0;
                document.getElementById('descricao').value = os.descricao || '';

                document.getElementById('os-id').textContent = `#${os.id}`;

                loadingSpinner.style.display = 'none';
                form.style.display = 'block';
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || `Erro ao buscar OS: ${response.statusText}`);
            }
        } catch (error) {
            console.error("Falha ao carregar os dados:", error);
            loadingSpinner.style.display = 'none';
            errorBox.innerText = `Erro ao carregar a OS ID ${OS_ID}: ${error.message}`;
            errorBox.style.display = 'block';
        }
    }

    // Salvar alterações
    async function handleEditFormSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('edit_os_form');
        const submitButton = form.querySelector('button[type="submit"]');
        const errorBox = document.getElementById('error-message');

        errorBox.style.display = 'none';
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

        const updateData = {
            valor_total: parseFloat(form.elements['valor_total'].value),
            status: parseInt(form.elements['status'].value),
            descricao: form.elements['descricao'].value
        };

        try {
            const body = JSON.stringify(updateData);
            const response = await apiFetch(OS_UPDATE_ENDPOINT, {
                method: 'PUT',
                body,
                headers: { 'Content-Type': 'application/json' }
            });

            if (response.ok) {
                window.location.href = '{{ route('servicos.servicos') }}';
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
            submitButton.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
        }
    }

    // Deletar OS
    async function handleDeleteFormSubmit(event) {
        event.preventDefault();

        if (!confirm('Tem certeza que deseja excluir esta Ordem de Serviço? Esta ação não pode ser desfeita.')) {
            return;
        }

        const form = document.getElementById('delete_os_form');
        const submitButton = form.querySelector('button[type="submit"]');
        const errorBox = document.getElementById('error-message');

        errorBox.style.display = 'none';
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deletando...';

        try {
            const response = await apiFetch(OS_DELETE_ENDPOINT, {
                method: 'DELETE'
            });

            if (response.ok) {
                window.location.href = '{{ route('servicos.servicos') }}';
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
            submitButton.innerHTML = '<i class="fas fa-trash"></i> Excluir OS';
        }
    }

    // Inicialização
    document.addEventListener('DOMContentLoaded', () => {
        if (OS_ID && OS_ID !== 'ID_Placeholder') {
            loadOsData();
            document.getElementById('edit_os_form').addEventListener('submit', handleEditFormSubmit);
            document.getElementById('delete_os_form').addEventListener('submit', handleDeleteFormSubmit);
        } else {
            document.getElementById('os-form-container').innerHTML =
                '<div class="alert alert-danger">ID da ordem de serviço inválido.</div>';
        }
    });
</script>
@endpush
