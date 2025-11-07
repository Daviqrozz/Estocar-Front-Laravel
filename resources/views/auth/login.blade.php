@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('title','Login')

@section('auth_header', 'Faça login para acessar o sistema')


@section('auth_body')

    <form id="login-form">
        
        <div class="input-group mb-3">
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="E-mail" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>

        <div class="input-group mb-3">
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Senha" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        
        {{-- Área para exibir mensagens de erro da API ou de conexão --}}
        <div id="error-message" class="alert alert-danger d-none" role="alert"></div>

        <button type="submit" id="login-button" class="btn btn-primary btn-block">
            <span id="button-text">Entrar</span>
            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </form>
@endsection

@section('auth_footer')
    <p class="my-0">
        <a href={{ route('register') }}>Não possui uma conta?</a>
    </p>
@endsection


@section('js')
<script>
    const API_URL = 'http://estocar-1.test'; 

    document.getElementById('login-form').addEventListener('submit', async (event) => {
        // Previne o envio padrão do formulário do navegador
        event.preventDefault(); 

        const form = event.target;
        const email = form.email.value;
        const password = form.password.value;
        const errorMessage = document.getElementById('error-message');
        const loginButton = document.getElementById('login-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        // Limpa mensagens de erro anteriores
        errorMessage.classList.add('d-none');
        errorMessage.textContent = '';
        
        // Desabilita o botão e mostra o spinner
        loginButton.disabled = true;
        buttonText.classList.add('d-none');
        spinner.classList.remove('d-none');

        try {
            // Requisição POST para o endpoint de login da sua API
            const response = await fetch(`${API_URL}/api/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password,
                    device_name:'estocar-frontend-login'
                })
            });

            const data = await response.json(); 
        
            if (response.ok) {
                const token = data.token;
                
                if (token) {
                    // 1. Armazena o token no armazenamento local (localStorage)
                    localStorage.setItem('api_token', token);
                    
                    // 2. Opcional: Armazenar dados básicos do usuário
                    if (data.user) {
                        localStorage.setItem('user_data', JSON.stringify(data.user));
                    }
                    
                    // 3. Sucesso: Redireciona para a página principal
                    window.location.href = '{{ route('home') }}';
                } else {
                    throw new Error('Token não recebido da API.');
                }
            } else {
                // Se a API retornar um erro (401, 422, etc.)
                let message = data.msg || 'Credenciais inválidas';
                errorMessage.textContent = message;
                errorMessage.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Erro de conexão ou requisição:', error);
            errorMessage.textContent = 'Não foi possível conectar ao servidor de autenticação.';
            errorMessage.classList.remove('d-none');
        } finally {
            // Restaura o estado do botão
            loginButton.disabled = false;
            buttonText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    });

    // Verifica se o token já existe ao carregar a página

    document.addEventListener('DOMContentLoaded', () => {
        const token = localStorage.getItem('api_token');
        if (token) {
            // Se o token existe, redireciona imediatamente para a home
            window.location.href = '{{ route('home') }}';
        }
    });

</script>
@endsection
