@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
    <div class="d-flex justify-content-between">

        <h2>Editar cliente - <span id="cliente-id">{{ $clienteID }}</span></h2>

        <div class="" id="delete-button-container">
            <form id="delete_cliente_form">
                <button type="submit" id="delete_cliente_btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Excluir Cliente
                </button>
            </form>
        </div>
    </div>

@stop

@section('content')
    @include('layouts.token_check')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dados do Cliente</h3>
        </div>
        <div class="card-body" id="cliente_form_container">

            <form id="form_edit_cliente">

                <div class="form-group">
                    <label for="marca">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control"
                        placeholder="Ex: João da silva pereira" required>
                </div>

                <div class="form-group">
                    <label for="modelo">Cpf</label>
                    <input type="text" name="cpf" id="cpf" class="form-control" placeholder="Ex: 00000000000"
                        required>
                </div>

                <div class="form-group">
                    <label for="ano">Telefone</label>
                    <input type="number" name="telefone" id="telefone" class="form-control" placeholder="Ex: 85911111111"
                        required>
                </div>

                <div class="form-group">
                    <label for="cor">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        placeholder="Ex: email@email.com" required>
                </div>


                <div class="form-group">
                    <label for="cor">Endereco</label>
                    <input type="text" name="endereco" id="endereco" class="form-control"
                        placeholder="Ex: rua dos anjos, 51" required>
                </div>


                <div class="d-flex justify-content-between mt-4">
                    <a href="/clientes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>

            <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>
            <div class="text-center" id="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x"></i> Carregando dados do cliente...
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        const CLIENTE_ID = '{{ $clienteID }}';
        const API_URL = 'http://estocar-1.test/api';
        const CAR_FETCH_ENDPOINT = `/lista/clientes/${CLIENTE_ID}`; // Endpoint GET para buscar um
        const CAR_UPDATE_ENDPOINT = `/editar/cliente/${CLIENTE_ID}`; // Endpoint PUT para atualizar 
        const CAR_DELETE_ENDPOINT = `/deletar/cliente/${CLIENTE_ID}` //Endopoint DELETE para deletar  

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

        async function loadClienteData() {

            const formContainer = document.getElementById('cliente_form_container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const form = document.getElementById('form_edit_cliente');
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

                    const cliente = data.cliente || data;

                    // Preenche o formulário com os dados
                    document.getElementById('nome').value = cliente.nome || '';
                    document.getElementById('cpf').value = cliente.cpf || '';
                    document.getElementById('telefone').value = cliente.telefone || '';
                    document.getElementById('email').value = cliente.email || '';
                    document.getElementById('endereco').value = cliente.endereco|| '';

                    loadingSpinner.style.display = 'none';
                    form.style.display = 'block';

                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar cliente: ${response.statusText}`);
                }

            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                loadingSpinner.style.display = 'none';
                errorBox.innerText = `Erro ao carregar o cliente ID ${CLIENTE_ID}: ${error.message}`;
                errorBox.style.display = 'block';
            }
        }

        async function handleEditFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('form_edit_cliente');
            const submitButton = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerText = 'Salvando...';


            // Coleta os dados do focpfio
            const updateData = {
                nome: form.elements['nome'].value,
                cpf: form.elements['cpf'].value,
                telefone: form.elements['telefone'].value,
                email: form.elements['email'].value,
                endereco: form.elements['endereco'].value,
            };


            try {
                const response = await apiFetch(CAR_UPDATE_ENDPOINT, {
                    method: 'PUT',
                    body: updateData
                });

                if (response.ok) {
                    // Redireciona para a lista após o sucesso
                    window.location.href = '{{ route('clientes.clientes') }}';
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

            const form = document.getElementById('delete_cliente_form');
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
                    window.location.href = '{{ route('clientes.clientes') }}';
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
            if (CLIENTE_ID && CLIENTE_ID !== 'ID_Placeholder') {
                loadClienteData();

                document.getElementById('form_edit_cliente').addEventListener('submit', handleEditFormSubmit);
                document.getElementById('delete_cliente_form').addEventListener('submit', handleDeleteFormSubmit);
            } else {
                document.getElementById('cliente-form-container').innerHTML =
                    '<div class="alert alert-danger">ID do cliente inválido.</div>';
            }
        });
    </script>
@endpush
