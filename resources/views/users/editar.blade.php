@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h2>Editar Usuário - <span id="user-id">{{ $usuarioID }}</span></h2>

        <div class="" id="delete-button-container">
            <form id="delete_user_form">
                <button type="submit" id="delete_user_btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Excluir Usuário
                </button>
            </form>
        </div>
    </div>
@stop

@section('content')
    @include('layouts.token_check')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dados do Usuário</h3>
        </div>
        <div class="card-body" id="user_form_container">
            <form id="form_edit_user">

                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nome do usuário"
                        required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="email@dominio.com"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Senha (Deixe em branco para não alterar)</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Nova Senha">
                </div>

                <div class="form-group">
                    <label for="cargo_id">Cargo</label>
                    <select name="cargo_id" id="cargo_id" class="form-control" required>
                        <option value="">Selecione um Cargo</option>
                        <option value="ADMIN">ADMIN</option>
                        <option value="VENDEDOR">VENDEDOR</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="/usuarios" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>

                    <button type="submit" class="btn btn-success" id="save_user_btn">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>

            <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>
            <div class="text-center" id="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x"></i> Carregando dados do usuário...
            </div>
        </div>
    </div>
@stop
@push('js')
    <script>
        const USER_ID = '{{ $usuarioID }}';
        const API_URL = 'http://estocar-1.test/api';

        const USER_FETCH_ENDPOINT = `/lista/users/${USER_ID}`;
        const USER_UPDATE_ENDPOINT = `/editar/usuario/${USER_ID}`;
        const USER_DELETE_ENDPOINT = `/deletar/user/${USER_ID}`

        // Função de utilidade para mapear ID para Nome (para carregar o formulário)
        function getCargoNameFromId(cargoId) {
            switch (parseInt(cargoId)) {
                case 1:
                    return 'ADMIN';
                case 2:
                    return 'VENDEDOR';
                default:
                    return '';
            }
        }

        // Função de utilidade para mapear Nome para ID (para submeter o formulário)
        function getCargoIdFromName(cargoName) {
            switch (cargoName) {
                case 'ADMIN':
                    return 1;
                case 'VENDEDOR':
                    return 2;
                default:
                    return null;
            }
        }


        async function apiFetch(endpoint, options = {}) {
            const token = localStorage.getItem('api_token');
            const fetchUrl = endpoint.includes(API_URL) ? endpoint : `${API_URL}${endpoint}`;

            if (!token) {
                window.location.href = '{{ route('login') }}';
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

        async function loadUserData() {

            const formContainer = document.getElementById('user_form_container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const form = document.getElementById('form_edit_user');
            const errorBox = document.getElementById('error-message');

            loadingSpinner.style.display = 'block';
            form.style.display = 'none';
            errorBox.style.display = 'none';

            try {
                const response = await apiFetch(USER_FETCH_ENDPOINT, {
                    method: 'GET'
                });

                if (response.ok) {
                    const data = await response.json();
                    const user = data.user || data;


                    // 1. Obtém o ID numérico do cargo
                    const cargoId = user.cargos && user.cargos.length > 0 ?
                        user.cargos[0].id :
                        null;

                    // 2. Converte o ID para o NOME do cargo ('ADMIN' ou 'VENDEDOR')
                    const cargoName = getCargoNameFromId(cargoId);


                    document.getElementById('name').value = user.name || user.nome || '';
                    document.getElementById('email').value = user.email || '';

                    // 3. Define o valor do select usando o NOME do cargo
                    document.getElementById('cargo_id').value = cargoName;


                    loadingSpinner.style.display = 'none';
                    form.style.display = 'block';

                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar usuário: ${response.statusText}`);
                }

            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                loadingSpinner.style.display = 'none';
                errorBox.innerText = `Erro ao carregar o usuário ID ${USER_ID}: ${error.message}`;
                errorBox.style.display = 'block';
            }
        }


        async function handleEditFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('form_edit_user');
            const submitButton = document.getElementById('save_user_btn');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-sync fa-spin"></i> Salvando...';


            const selectedCargoName = form.elements['cargo_id'].value;
            // Usa a função para converter o NOME para o ID numérico (1 ou 2) antes de enviar
            const cargoIdToSubmit = getCargoIdFromName(selectedCargoName);

            const updateData = {
                name: form.elements['name'].value,
                email: form.elements['email'].value,
                // Envia o ID numérico
                cargo_id: cargoIdToSubmit
            };

            const passwordInput = form.elements['password'].value;
            if (passwordInput) {
                updateData.password = passwordInput;
            }



            try {
                const response = await apiFetch(USER_UPDATE_ENDPOINT, {
                    method: 'PUT',
                    body: updateData
                });

                if (response.ok) {
                    alert("Usuário atualizado com sucesso!");
                    window.location.href = '{{ url('/usuarios') }}';
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

        async function handleDeleteFormSubmit(event) {
            event.preventDefault();

            if (!confirm("Tem certeza que deseja EXCLUIR este usuário? Esta ação é irreversível.")) {
                return;
            }

            const form = document.getElementById('delete_user_form');
            const submitButton = document.getElementById('delete_user_btn');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-sync fa-spin"></i> Excluindo...';

            try {
                const response = await apiFetch(USER_DELETE_ENDPOINT, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    alert("Usuário excluído com sucesso!");
                    window.location.href = '{{ url('/users') }}';
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
                submitButton.innerHTML = '<i class="fas fa-trash"></i> Excluir Usuário';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (USER_ID) {
                loadUserData();

                document.getElementById('form_edit_user').addEventListener('submit', handleEditFormSubmit);
                document.getElementById('delete_user_form').addEventListener('submit', handleDeleteFormSubmit);
            } else {
                document.getElementById('user_form_container').innerHTML =
                    '<div class="alert alert-danger">ID do Usuário inválido.</div>';
            }
        });
    </script>
@endpush
