@include('layouts.token_check')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de clientes</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cpf</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Endereço</th>
                    <th style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody id="clientes_body">
                <tr>
                    <td colspan="7" class="text-center text-muted">Carregando dados...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@push('js')
    <script>
        const API_URL = 'http://estocar-1.test/api'
        const CLIENTES_ENDPOINT = '/lista/clientes'

        async function apiFetch(endpoint, options = {}) {
            const token = localStorage.getItem('api_token')
            const fetchUrl = `${API_URL}${endpoint}`;

            if (!token) {
                window.location.href = '{{ route('login') }}';
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
                body: options.body
            });

            if (response.status === 401 || response.status === 403) {
                console.error("Autenticação falhou. Token inválido/expirado.");
                localStorage.removeItem('api_token');
                // Redireciona para o login
                window.location.href = '{{ route('login') }}';
                return Promise.reject(new Error("Não autorizado."));
            }

            return response;

        }

        function renderClientesTable(clientes) {
            const body = document.getElementById('clientes_body');
            body.innerHTML = '';

            clientes.forEach(cliente => {

                // Monta a linha da tabela
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${cliente.id}</td>
                <td>
                    <a href="{{ url('/clientes/editar') }}/${cliente.id}" class="text-primary">
                        ${cliente.nome}
                    </a>
                </td>
                <td>${cliente.cpf}</td>
                <td>${cliente.telefone}</td>
                <td>${cliente.email}</td>
                <td>${cliente.endereco}</td>
                <td>
                    <button class="btn btn-xs btn-info" onclick="viewCliente(${cliente.id})">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-xs btn-danger" onclick="deleteCliente(${cliente.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
                body.appendChild(row);
            });
        }
        async function fetchClientes() {
            try {
                const response = await apiFetch(CLIENTES_ENDPOINT, {
                    method: 'GET'
                });

                if (response.ok) {
                    const data = await response.json();
                    renderClientesTable(data.clientes || data);
                } else {
                    document.getElementById('clientes_body').innerHTML =
                        `<tr><td colspan="7" class="text-center text-danger">Erro ao carregar os clientes: ${response.statusText}</td></tr>`;
                }

            } catch (error) {
                console.error("Falha fatal no Fetch:", error);

                document.getElementById('clientes_body').innerHTML =
                    `<tr><td colspan="7" class="text-center text-danger">Falha de comunicação com o servidor.</td></tr>`;
            }
        }

        document.addEventListener('DOMContentLoaded', fetchClientes);

        const EDIT_URL_BASE = "{{ url('/clientes/editar') }}";


        window.viewCliente = (id) => {
            window.location.href = `${EDIT_URL_BASE}/${id}`;
        };

        async function deleteCliente(id) {
            if (!confirm('Deseja realmente exluir este cliente?')) return;

            try {
                const response = await apiFetch(`/deletar/cliente/${id}`, {
                    method: 'DELETE',
                })

                if (!response.ok) {
                    const errorData = await response.json()
                    throw new Error(errorData.message || 'Erro ao deletar cliente')
                }

                alert(`Cliente ${id} deletado com sucesso`)
                window.location.href = '{{url('/clientes')}}'

            } catch (error) {
                console.error('Falha ao deletar cliente:', error);
                alert(`Erro ao deletar: ${error.message}`);
            }
        }
    </script>
