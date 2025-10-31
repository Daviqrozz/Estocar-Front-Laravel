
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saindo...</title>
</head>
<body>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <p>Aguarde, efetuando logout...</p>
    </div>

    <script>
        // Este script é executado quando o usuário acessa /logout via URL ou botão.

        // 1. Limpa o token do localStorage (essencial para o Frontend)
        localStorage.removeItem('api_token');
        localStorage.removeItem('user_data');
        
        // 2. Redireciona imediatamente para a tela de login.
        // Usamos '{{ route('login') }}' para pegar a URL correta do Laravel.
        window.location.href = '{{ route('login') }}'; 
    </script>
</body>
</html>

