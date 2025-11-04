@extends('adminlte::page')

@section('title', 'Editar Carro')

@section('content_header')
    <div class="d-flex justify-content-between">

        <h2>Editar Veículo - <span id="carro-id">{{ $carroId }}</span></h2>

        <div class="" id="delete-button-container">
            <form id="delete_carro_form">
                <button type="submit" id="delete_carro_btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Excluir Veículo
                </button>
            </form>
        </div>
    </div>

@stop

@section('content')
    @include('layouts.token_check')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dados do Veículo</h3>
        </div>
        <div class="card-body" id="carro-form-container">

            <div class="text-center" id="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x"></i> Carregando dados do carro...
            </div>

            <form id="edit_carro_form" style="display:none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" id="marca" name="marca" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" id="modelo" name="modelo" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="preco">Valor (R$)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" step="0.01" name="preco" id="preco" class="form-control"
                                    placeholder="Ex: 75000" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modelo">Cor do veiculo</label>
                            <input type="text" id="cor" name="cor" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modelo">Ano de fabricação</label>
                            <input type="text" id="ano" name="ano" class="form-control" required>
                        </div>
                    </div>

                </div>

                <label for="status">Status</label>
                <select id="status" name="status"
                    class="status-select form-control form-control-sm text-white font-weight-bold">
                    <option value="disponivel" id="available_option_status">Disponível</option>
                    <option value="indisponivel" id="unavailable_option_status">Indísponivel</option>
                </select>

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
        const CARRO_ID = '{{ $carroId }}';
        const API_URL = 'http://estocar-1.test/api';
        const CAR_FETCH_ENDPOINT = `/lista/carros/${CARRO_ID}`; // Endpoint GET para buscar um carro
        const CAR_UPDATE_ENDPOINT = `/editar/carro/${CARRO_ID}`; // Endpoint PUT para atualizar o carro
        const CAR_DELETE_ENDPOINT = `/deletar/carro/${CARRO_ID}` //Endopoint DELETE para deletar o carro

        //Função padrão para realizar uma requisição para a API
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

        async function loadCarData() {

            const formContainer = document.getElementById('carro-form-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const form = document.getElementById('edit_carro_form');
            const errorBox = document.getElementById('error-message');

            loadingSpinner.style.display = 'block';
            form.style.display = 'none';
            errorBox.style.display = 'none';

            try {
                const response = await apiFetch(CAR_FETCH_ENDPOINT, {
                    method: 'GET'
                });

                if (response.ok) {
                    const data = await response.json();

                    const carro = data.carro || data;
                    
                    const valorFormatado = new Intl.NumberFormat('pt-BR', {
                    }).format(carro.preco || 0);

                    // Preenche o formulário com os dados
                    document.getElementById('marca').value = carro.marca || '';
                    document.getElementById('modelo').value = carro.modelo || '';
                    document.getElementById('ano').value = carro.ano || '';
                    document.getElementById('preco').value = valorFormatado || '';
                    document.getElementById('cor').value = carro.cor || '';

                    const statusSelect = document.getElementById('status');

                    statusSelect.value = (carro.status == 1) ? 'disponivel' : 'indisponivel';

                    loadingSpinner.style.display = 'none';
                    form.style.display = 'block';

                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar carro: ${response.statusText}`);
                }

            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                loadingSpinner.style.display = 'none';
                errorBox.innerText = `Erro ao carregar o carro ID ${CARRO_ID}: ${error.message}`;
                errorBox.style.display = 'block';
            }
        }

        async function handleEditFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('edit_carro_form');
            const submitButton = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerText = 'Salvando...';

            function ValorBruto(ValorFormatado) {
                if (!ValorFormatado) return 0;

                let cleanString = ValorFormatado
                    .replace(/[R$]/g, '')
                    .trim()
                    .replace(/\./g, '');

                cleanString = cleanString.replace(/,/g, '.');

                return parseFloat(cleanString) || 0;
            }

            const precoInput = form.elements['preco'].value

            // Coleta os dados do formulário
            const updateData = {
                marca: form.elements['marca'].value,
                modelo: form.elements['modelo'].value,
                cor: form.elements['cor'].value,

                preco: ValorBruto(precoInput),

                ano: form.elements['ano'].value,
                status: (form.elements['status'].value === 'disponivel') ? 1 : 0
            };


            try {
                const response = await apiFetch(CAR_UPDATE_ENDPOINT, {
                    method: 'PUT',
                    body: updateData
                });

                if (response.ok) {
                    // Redireciona para a lista após o sucesso
                    window.location.href = '{{ route('carros.index') }}';
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


        //Handle para deletar veiculo

        async function handleDeleteFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('delete_carro_form');
            const submitButton = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerText = 'Salvando...';

            try {
                const response = await apiFetch(CAR_DELETE_ENDPOINT, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    // Redireciona para a lista após o sucesso
                    window.location.href = '{{ route('carros.index') }}';
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
                submitButton.innerText = 'Deletar';
            }
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            // Verifica se o ID é válido e inicia o carregamento dos dados
            if (CARRO_ID && CARRO_ID !== 'ID_Placeholder') {
                loadCarData();

                document.getElementById('edit_carro_form').addEventListener('submit', handleEditFormSubmit);
                document.getElementById('delete_carro_form').addEventListener('submit', handleDeleteFormSubmit);
            } else {
                document.getElementById('carro-form-container').innerHTML =
                    '<div class="alert alert-danger">ID do carro inválido.</div>';
            }
        });
    </script>
@endpush
