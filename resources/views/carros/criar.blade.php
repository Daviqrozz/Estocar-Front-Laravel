@extends('adminlte::page')

@section('title', 'Cadastrar Veículo')

@section('content_header')
    <h2>Cadastrar novo veículo</h2>
@endsection

@section('content')
    @include('layouts.token_check')

    <div class="card">
        <div class="card-body">
            <form id="form_create_car">

                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" name="marca" id="marca" class="form-control" placeholder="Ex: Toyota" required>
                </div>

                <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ex: Corolla"
                        required>
                </div>

                <div class="form-group">
                    <label for="ano">Ano</label>
                    <input type="number" name="ano" id="ano" class="form-control" placeholder="Ex: 2023"
                        required>
                </div>

                <div class="form-group">
                    <label for="cor">Cor do veiculo</label>
                    <input type="text" name="cor" id="cor" class="form-control" placeholder="Ex: Prata"
                        required>
                </div>

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

                <div class="d-flex justify-content-between mt-4">
                    <a href="/carros" class="btn btn-secondary">
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

        document.getElementById('form_create_car').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target; 
            const submitButton = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

            const createData = {
                marca: form.elements['marca'].value,
                modelo: form.elements['modelo'].value,
                cor: form.elements['cor'].value,
                ano: form.elements['ano'].value, 
                preco: form.elements['preco'].value,
                status: 1 
            };
            
            

            try {
                // 5. CORREÇÃO: Adiciona Headers de autenticação e Content-Type, e stringify no body
                const response = await fetch(`${API_URL}/criar/carro`, {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify(createData),
                });

                if (response.ok) {
                    // Redireciona para a lista
                    window.location.href = '{{ route('carros.index') }}'; 
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