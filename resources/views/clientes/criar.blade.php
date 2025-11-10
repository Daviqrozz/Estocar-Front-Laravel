@extends('adminlte::page')

@section('title', 'Cadastrar Veículo')

@section('content_header')
    <h2>Cadastrar novo cliente</h2>
@endsection

@section('content')
    @include('layouts.token_check')

    <div class="card">
        <div class="card-body">
            <form id="form_create_cliente">

                <div class="form-group">
                    <label for="marca">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" placeholder="Ex: João da silva pereira" required>
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
                    <input type="email" name="email" id="email" class="form-control" placeholder="Ex: email@email.com"
                        required>
                </div>

                
                <div class="form-group">
                    <label for="cor">Endereco</label>
                    <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Ex: rua dos anjos, 51"
                        required>
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
        </div>
    </div>
@endsection
@push('js')
    <script>
        const API_URL = 'http://estocar-1.test/api';
        
        // Função auxiliar para obter o Token, baseada no seu código anterior
        function getAuthHeaders(options = {}) {
            const token = localStorage.getItem('api_token');
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...(options.headers || {}),
            };

            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            return headers;
        }

        document.getElementById('form_create_cliente').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target; 
            const submitButton = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

            const createData = {
                nome: form.elements['nome'].value,
                cpf: form.elements['cpf'].value,
                telefone: form.elements['telefone'].value,
                email: form.elements['email'].value, 
                endereco: form.elements['endereco'].value,
            };
            
            

            try {
                // 5. CORREÇÃO: Adiciona Headers de autenticação e Content-Type, e stringify no body
                const response = await fetch(`${API_URL}/criar/cliente`, {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify(createData),
                });

                if (response.ok) {
                    // Redireciona para a lista
                    window.location.href = '{{ route('clientes.clientes') }}'; 
                } else if (response.status === 401 || response.status === 403) {
                     // Tratamento de autenticação
                     localStorage.removeItem('api_token');
                     window.location.href = '{{ route('login') }}';
                } else {
                    const errorData = await response.json();
                    // Mostra erros da validação do Laravel/API (se houver)
                    const errorMessage = errorData.message || (errorData.errors ? Object.values(errorData.errors).join('<br>') : 'Erro desconhecido ao salvar.');

                    errorBox.innerHTML = errorMessage;
                    errorBox.style.display = 'block';
                }
            } catch (error) {
                console.error("Falha ao salvar:", error);
                errorBox.innerText = `Falha na requisição: ${error.message}`;
                errorBox.style.display = 'block';
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-save"></i> Salvar';
            }
        });
    </script>
@endpush
