@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('auth_header','Faça seu cadastro')

@section('auth_body')

<form id="register-form">
        
        <div class="input-group mb-3">
            <input type="name" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nome completo" value="{{old('name')}}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                    
                </div>
            </div>
        </div>

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

        <button type="submit" id="register-button" class="btn btn-primary btn-block">
            <span id="button-text">Entrar</span>
            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </form>

@endsection

@section('auth_footer')
    <p class="my-0">
        <a href={{ route('login') }}>Ja possui uma conta?</a>
    </p>
@endsection

@section('js')
<script>

    const API_URL = 'http://estocar-1.test';

    document.getElementById('register-form').addEventListener('submit' ,async (event) => {
        event.preventDefault();

        const form = event.target;
        const name = form.name.value
        const email = form.email.value;
        const password = form.password.value;
        const errorMessage = document.getElementById('error-message');
        const loginButton = document.getElementById('register-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

       
        errorMessage.classList.add('d-none');
        errorMessage.textContent = '';

        loginButton.disabled = true;
        buttonText.classList.add('d-none');
        spinner.classList.remove('d-none')

        try {
            const response = await fetch(`${API_URL}/api/auth/register`,{
                method:'POST',
                headers:{
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body:JSON.stringify({
                    name:name,
                    email:email,
                    password:password,
                    device_name:'estocar-frontend-register'
                })
            });

            const data = await response.json();

            if (response.ok) {
                const token = data.token

              if (token) {
                 localStorage.setItem('api_token',token);

                    if (data.user) {
                        localStorage.setItem('user_data',data.user)
                    }
                    window.location.href = '{{route('home')}}'

              } else {
                 throw new Error('Token não recebido da API.');
              }
            } else {
                if (data.msg) {

                let message = data.msg || 'Credenciais inválidas';
                errorMessage.textContent = message;
                errorMessage.classList.remove('d-none');

                } if (data.message) {
                    let message = data.message || 'Credenciais inválidas';
                    errorMessage.textContent = message;
                    errorMessage.classList.remove('d-none');
                }
              
            }
             //Se a requisição falhar:
        } catch (error) {
            console.error('Erro de conexão ou requisição:', error);
            errorMessage.textContent = 'Não foi possível conectar ao servidor de autenticação.';
            errorMessage.classList.remove('d-none');
        } finally {
            loginButton.disabled = false;
            buttonText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    });

    document.addEventListener('DOMContentLoaded',() => {
        const token = localStorage.getItem('api_token');
        if (token) {
            window.location.href = '{{route('home')}}'
        }});

</script>
@endsection