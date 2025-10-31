@extends('adminlte::page')

@section('title', 'Carros')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h2>Carros cadastrados na plataforma</h2>
    <a href="{{ route('carros.criar') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Cadastrar
    </a>
</div>
@endsection

@section('content')

@include('layouts.token_check')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de veículos</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Ano</th>
              
                    <th>Status</th>
                    <th>Valor Fipe</th>
                    <th style="width: 100px;">Ações</th> {{-- Coluna para botões/ações --}}
                </tr>
            </thead>
            {{-- ESTE É O LOCAL ONDE O JAVASCRIPT INSERIRÁ OS DADOS --}}
            <tbody id="carros_body"> {{-- CORRIGIDO: Agora usa 'carros_body' --}}
                {{-- Linhas serão inseridas aqui pelo JS --}}
                <tr>
                    <td colspan="7" class="text-center text-muted">Carregando dados...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
    // URL da sua API (ajuste conforme o seu ambiente, usando a rota /api/lista/carros)
    const API_URL = 'http://estocar-1.test/api'; 
    const CAR_API_ENDPOINT = '/lista/carros'; 

    /**
     * Helper que adiciona o Authorization Header e trata erros 401/403.
     */
    async function  apiFetch(endpoint, options = {}) {
        const token = localStorage.getItem('api_token');
        const fetchUrl = `${API_URL}${endpoint}`;

        if (!token) {
            console.error("Token de API ausente.");
            // Redireciona via auth-check.blade.php ou aqui, se necessário
            return Promise.reject(new Error("Token de autenticação ausente.")); 
        }

        const response = await fetch(fetchUrl, {
            ...options,
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                ...(options.headers || {}),
            },
        });

        // Tratamento de falha de autenticação no servidor
        if (response.status === 401 || response.status === 403) {
            console.error("Autenticação falhou. Token inválido/expirado.");
            localStorage.removeItem('api_token');
            localStorage.removeItem('user_data');
            // Redireciona para o login
            window.location.href = '{{ route('login') }}';
            return Promise.reject(new Error("Não autorizado."));
        }

        return response;
    }


    /**
     * FUNÇÃO DE ESTILIZAÇÃO (MANTIDA)
     */
    function updateSelectColor(select) {
        const value = select.value;
        select.style.color = '#fff';
        select.style.borderColor = 'transparent';

        switch (value) {
            case 'disponivel':
                select.style.backgroundColor = '#28a745'; // Verde
                break;
            case 'manutencao':
                select.style.backgroundColor = '#ffc107'; // Amarelo
                break;
            case 'vendido':
                select.style.backgroundColor = '#6c757d'; // Cinza
                break;
            default:
                select.style.backgroundColor = '#007bff';
        }
    }

    /**
     * FUNÇÃO DE RENDERIZAÇÃO DA TABELA (O foreach JS)
     * @param {Array<Object>} carros - Lista de objetos carro
     */
    function renderCarTable(carros) {
        const body = document.getElementById('carros_body'); // CORRIGIDO: Agora busca 'carros_body'
        body.innerHTML = ''; // Limpa o "Carregando dados..."

        carros.forEach(carro => {
            // Monta as opções do select
            const statusOptions = `
                <option value="disponivel" ${carro.status === 'disponivel' ? 'selected' : ''}>Disponível</option>
                <option value="manutencao" ${carro.status === 'manutencao' ? 'selected' : ''}>Em manutenção</option>
                <option value="vendido" ${carro.status === 'vendido' ? 'selected' : ''}>Vendido</option>
            `;
            
            const valorFipeFormatado = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(carro.preco || 0);

            // Monta a linha da tabela
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${carro.id}</td>
                <td>
                    <a href="{{ url('/carros/editar') }}/${carro.id}" class="text-primary">
                        ${carro.marca} ${carro.modelo}
                    </a>
                </td>
                <td>${carro.ano}</td>
              
                <td>
                    <select class="status-select form-control form-control-sm text-white font-weight-bold" data-car-id="${carro.id}">
                        ${statusOptions}
                    </select>
                </td>
                <td>${valorFipeFormatado}</td>
                <td>
                    <button class="btn btn-xs btn-info" onclick="viewCar(${carro.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-xs btn-danger" onclick="deleteCar(${carro.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            body.appendChild(row);

            // Encontra e inicializa o select recém-criado para estilização
            const newSelect = row.querySelector('.status-select');
            updateSelectColor(newSelect);
            newSelect.addEventListener('change', () => updateSelectColor(newSelect));
        });
    }

    /**
     * FUNÇÃO PRINCIPAL: Busca os dados na API e chama a renderização.
     */
    async function fetchCarros() {
        try {
            const response = await apiFetch(CAR_API_ENDPOINT, { method: 'GET' });
            
            if (response.ok) {
                const data = await response.json();
                renderCarTable(data.carros || data); // Assumindo que a API pode retornar {carros: [...]} ou apenas [...]
            } else {
                document.getElementById('carros_body').innerHTML = `<tr><td colspan="7" class="text-center text-danger">Erro ao carregar os carros: ${response.statusText}</td></tr>`;
            }

        } catch (error) {
            console.error("Falha fatal no Fetch:", error);
            // Se o erro foi 'Token de autenticação ausente', o apiFetch já tratou o redirecionamento.
            document.getElementById('carros_body').innerHTML = `<tr><td colspan="7" class="text-center text-danger">Falha de comunicação com o servidor.</td></tr>`;
        }
    }


    // 1. Executa o fetch dos carros assim que o documento estiver pronto
    document.addEventListener('DOMContentLoaded', fetchCarros);
    
    // 2. Cria stubs (funções vazias) para as ações de edição/deleção.
    // Você implementará a lógica dessas funções futuramente.
    window.viewCar = (id) => { console.log(`Visualizar carro ID: ${id}`); /* Implementar modal ou redirecionamento */ };
    window.deleteCar = (id) => { console.log(`Deletar carro ID: ${id}`); /* Implementar chamada DELETE */ };

</script>
@endpush
