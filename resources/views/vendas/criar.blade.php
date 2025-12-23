@extends('adminlte::page')
@include('layouts.token_check')
@section('title', 'Nova Venda')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h2>Nova venda</h2>

        <div class="" id="delete-button-container">
            <form id="delete_venda_form">
                <button type="submit" id="delete_venda_btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Cancelar
                </button>
            </form>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dados da Venda</h3>
        </div>
        <div class="card-body" id="venda-form-container">
            <form id="create_venda_form">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-car text-primary"></i> Carro Vendido</label>
                            <select name="carro_select" id="carro_select" class="form-control">
                                <option value="carro_default" id="carro_default_option">Selecione o veiculo</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-user text-primary"></i> Cliente</label>
                            <select name="cliente_select" id="cliente_select" class="form-control">
                                <option value="cliente_default" id="cliente_default_option">Selecione o cliente</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-user-tie text-primary"></i> Vendedor</label>
                            <input type="text" id="user_info" class="form-control" readonly style="background: #f8f9fa;">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valor_venda"><i class="fas fa-dollar-sign text-success"></i> Valor da Venda
                                (R$)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" step="0.01" name="valor_venda" id="valor_venda"
                                    class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status"><i class="fas fa-info-circle text-warning"></i> Status</label>
                            <select id="status" name="status"
                                class="status-select form-control form-control-sm text-white font-weight-bold">
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
        const API_URL = 'http://estocar-1.test/api';
        const CLIENTE_FETCH_ENDPOINT = '/lista/clientes'
        const CARRO_FETCH_ENDPOINT = '/lista/carros'
        const VENDA_CREATE_ENDPOINT = '/criar/venda'

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
                body: typeof options.body === 'object' && defaultHeaders['Content-Type'] ? JSON.stringify(
                    options.body) : options.body,
            });

            if (response.status === 401 || response.status === 403) {
                localStorage.removeItem('api_token');
                window.location.href = '{{ route('login') }}';
                return Promise.reject(new Error("Não autorizado."));
            }

            return response;
        }

        // Carrega clientes
        async function loadClientes() {
            try {
                const response = await apiFetch(CLIENTE_FETCH_ENDPOINT, {
                    method: 'GET'
                })

                if (response.ok) {
                    const data = await response.json()
                    const clientes = data.clientes

                    const cliente_select = document.getElementById('cliente_select')

                    clientes.forEach(cliente => {
                        const cliente_option = document.createElement('option')

                        cliente_option.textContent = `${cliente.id} - ${cliente.nome}`

                        cliente_option.value = `${cliente.id}`

                        cliente_select.appendChild(cliente_option)

                    });
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar dado: ${response.statusText}`);
                }
            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                const cliente_default_option = document.getElementById('cliente_default_option')

                cliente_default_option.value = 'Erro ao carregar Dados'
            }
        }

        async function loadCarros() {
            try {

                const response = await apiFetch(CARRO_FETCH_ENDPOINT, {
                    method: 'GET'
                })

                if (response.ok) {
                    const data = await response.json()
                    if (data.carros.status == 1) {
                        
                    }
                   
                    const carros = data.carros

                    

                    const carro_select = document.getElementById('carro_select')

                    carros.filter(carro => carro.status === 1).forEach(carro => {
                        const carro_option = document.createElement('option')
                        carro_option.textContent = `${carro.id} - ${carro.marca} ${carro.modelo}`

                        carro_option.value = `${carro.id}`

                        carro_select.appendChild(carro_option)

                    });
                } else {

                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar dado: ${response.statusText}`);
                }

            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                const carro_default_option = document.getElementById('carro_default_option')

                carro_default_option.value = 'Erro ao carregar Dados'
            }
        }

        async function loadUser() {
            const response = await apiFetch('/me', {
                method: 'GET'
            })

            if (response.ok) {
                const data = await response.json()
                const user = data.user


                document.getElementById('user_info').value = user?.name || '—';

            }
        }
        // Salvar alterações
        async function handleSaveFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('create_venda_form');
            const submitButton = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerText = 'Salvando...';

            if (
                form.elements['cliente_select'].value === 'cliente_default' ||
                form.elements['carro_select'].value === 'carro_default'
            ) {
                errorBox.innerText = 'Selecione um cliente e um carro válidos.';
                errorBox.style.display = 'block';
                submitButton.disabled = false;
                submitButton.innerText = 'Salvar Alterações';
                return;
            }
            
            const createData = {
                cliente_id: parseInt(form.elements['cliente_select'].value),
                carro_id: parseInt(form.elements['carro_select'].value),
                valor_venda: parseFloat(form.elements['valor_venda'].value),
                status: parseInt(form.elements['status'].value)
            };

            try {
                const response = await apiFetch(VENDA_CREATE_ENDPOINT, {
                    method: 'POST',
                    body: createData
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
        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            loadClientes()
            loadCarros()
            loadUser()
            document
                .getElementById('create_venda_form')
                .addEventListener('submit', handleSaveFormSubmit);
        });
    </script>
@endpush
