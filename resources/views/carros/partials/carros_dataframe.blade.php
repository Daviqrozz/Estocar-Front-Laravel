@extends('adminlte::page')

@section('title', 'Carros')

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
                        <th>Valor</th>
                        <th>Cor</th>
                        <th>Status</th>
                        <th style="width: 100px;">Ações</th>
                </thead>

                <tbody id="carros_body">

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
        const API_URL = 'http://estocar-1.test/api';
        const CAR_API_ENDPOINT = '/lista/carros';


        async function apiFetch(endpoint, options = {}) {
            const token = localStorage.getItem('api_token');
            const fetchUrl = `${API_URL}${endpoint}`;

            if (!token) {
                console.error("Token de API ausente.");
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
                // Redireciona para o login
                window.location.href = '{{ route('login') }}';
                return Promise.reject(new Error("Não autorizado."));
            }

            return response;
        }

        function updateSelectColor(select) {
            const value = select.value;
            select.style.color = '#fff';
            select.style.borderColor = 'transparent';

            switch (value) {
                case 'disponivel':
                    select.style.backgroundColor = '#28a745'; // Verde
                    break;
                case 'indisponivel':
                    select.style.backgroundColor = '#ff0000 '; // Cinza
                    break;
                default:
                    select.style.backgroundColor = '#007bff';
            }
        }

        function renderCarTable(carros) {
            const body = document.getElementById('carros_body');
            body.innerHTML = '';

            carros.forEach(carro => {

                const statusOptions = `
                <option value="disponivel" ${carro.status === 1 ? 'selected' : ''}>Disponível</option>
                <option value="indisponivel" ${carro.status === 0 ? 'selected' : ''}>Indisponivel</option>
            `;

                const valorFormatado = new Intl.NumberFormat('pt-BR', {
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
                <td>${valorFormatado}</td>
                <td>${carro.cor}</td>
                <td>
                    <select class="status-select form-control form-control-sm text-white font-weight-bold" data-car-id="${carro.id}">
                        ${statusOptions}
                    </select>
                </td>
                <td>
                    <button class="btn btn-xs btn-info" onclick="viewCar(${carro.id})">
                        <i class="fas fa-pen"></i>
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
                newSelect.addEventListener('change', (e) => {
                    const novoStatus = e.target.value === 'disponivel' ? 1 :
                    0;
                    updateSelectColor(newSelect);
                    updateStatusCarro(carro.id, novoStatus);
                });
            });
        }

        async function fetchCarros() {
            try {
                const response = await apiFetch(CAR_API_ENDPOINT, {
                    method: 'GET'
                });

                if (response.ok) {
                    const data = await response.json();
                    renderCarTable(data.carros || data);
                } else {
                    document.getElementById('carros_body').innerHTML =
                        `<tr><td colspan="7" class="text-center text-danger">Erro ao carregar os carros: ${response.statusText}</td></tr>`;
                }

            } catch (error) {
                console.error("Falha fatal no Fetch:", error);

                document.getElementById('carros_body').innerHTML =
                    `<tr><td colspan="7" class="text-center text-danger">Falha de comunicação com o servidor.</td></tr>`;
            }
        }

        document.addEventListener('DOMContentLoaded', fetchCarros);

        window.updateStatusCarro = async (id, novoStatus) => {
            try {
                const response = await apiFetch(`/editar/carro/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: novoStatus
                    })
                });

                if (response.ok) {
                    console.log(`Carro ${id} atualizada para status ${novoStatus}`);
                } else {
                    const errorData = await response.json().catch(() => ({
                        message: 'Erro desconhecido.'
                    }));
                    throw new Error(errorData.message || response.statusText);
                }
            } catch (error) {
                console.error("Falha ao atualizar status:", error);
                alert(`Falha ao atualizar vendac: ${error.message}`);
                fetchCarros();
            }
        };

        const EDIT_URL_BASE = "{{ url('/carros/editar') }}";

        window.viewCar = (id) => {
            window.location.href = `${EDIT_URL_BASE}/${id}`;
        };
        window.deleteCar = async (id) => {


            if (!confirm(`Tem certeza que deseja deletar o carro ID ${id}? Esta ação não pode ser desfeita.`)) {
                return;
            }


            const rowElement = document.querySelector(`[onclick="deleteCar(${id})"]`).closest('tr');
            const originalHtml = rowElement.innerHTML;
            rowElement.style.opacity = 0.5;

            const CAR_DELETE_ENDPOINT = `/deletar/carro/${id}`;

            try {
                const response = await apiFetch(CAR_DELETE_ENDPOINT, {
                    method: 'DELETE'
                });

                if (response.ok) {

                    rowElement.remove();
                    alert(`Carro ID ${id} deletado com sucesso!`);
                } else {
                    const errorData = await response.json().catch(() => ({
                        message: 'Erro desconhecido.'
                    }));
                    throw new Error(errorData.message || response.statusText);
                }
            } catch (error) {
                console.error("Falha ao deletar:", error);
                alert(`Falha ao deletar carro ID ${id}: ${error.message}`);

                if (rowElement) {
                    rowElement.style.opacity = 1;
                }

            }

        };
    </script>
@endpush
