@extends('adminlte::page')

@section('title', 'Cadastrar Usuário')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Cadastrar Novo Usuário</h2>
    <a href="/users" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>
@endsection

@section('content')
@include('layouts.token_check')

<div class="card bg-light shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Informações do Usuário</h3>
    </div>

    <div class="card-body">
        <form id="form_create_user"> 

            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Digite o nome completo" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Digite uma senha segura" required>
            </div>

            <div class="form-group">
                <label for="cargo_id">Cargo / Função</label>
                <select name="cargo_id" id="cargo_id" class="form-control" required>
                    <option value="">Selecione um Cargo</option>
                    <option value="ADMIN">ADMIN</option>
                    <option value="VENDEDOR">VENDEDOR</option>
                </select>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-success" id="save_user_btn">
                    <i class="fas fa-save"></i> Cadastrar Usuário
                </button>
                <a href="/users" class="btn btn-danger ml-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
        <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>
    </div>
</div>
@endsection

@push('js')
<script>
    const API_URL = 'http://estocar-1.test/api';
    const USER_CREATE_ENDPOINT = `/criar/usuario`;

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
    
    // Função apiFetch (adaptada para requisições POST)
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
            'Content-Type': 'application/json',
        };

        const response = await fetch(fetchUrl, {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {}),
            },
            body: JSON.stringify(options.body),
        });

        if (response.status === 401 || response.status === 403) {
            localStorage.removeItem('api_token');
            window.location.href = '{{ route('login') }}';
            return Promise.reject(new Error("Não autorizado."));
        }

        return response;
    }
    
  
    async function handleCreateFormSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form_create_user');
        const submitButton = document.getElementById('save_user_btn');
        const errorBox = document.getElementById('error-message');

        errorBox.style.display = 'none';
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-sync fa-spin"></i> Cadastrando...';
     
        const selectedCargoName = form.elements['cargo_id'].value;
        const cargoIdToSubmit = getCargoIdFromName(selectedCargoName);

        if (!cargoIdToSubmit) {
            errorBox.innerText = "Por favor, selecione um cargo válido.";
            errorBox.style.display = 'block';
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-save"></i> Cadastrar Usuário';
            return;
        }

        const createData = {
            name: form.elements['name'].value,
            email: form.elements['email'].value,
            password: form.elements['password'].value,
    
            cargo: cargoIdToSubmit
        };
        
        try {
    
            const response = await apiFetch(USER_CREATE_ENDPOINT, {
                method: 'POST',
                body: createData
            });

            if (response.ok) {
                alert("Usuário cadastrado com sucesso!");
             
                window.location.href = '{{ url('/usuarios') }}'; 
            } else {
                const errorData = await response.json();
            
                const errorMessage = errorData.message || 'Erro desconhecido ao cadastrar.';
                errorBox.innerText = errorMessage + (errorData.errors ? "\n" + Object.values(errorData.errors).flat().join("\n") : "");
                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error("Falha ao cadastrar:", error);
            errorBox.style.display = 'block';
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-save"></i> Cadastrar Usuário';
        }
    }


    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('form_create_user').addEventListener('submit', handleCreateFormSubmit);
    });
</script>
@endpush