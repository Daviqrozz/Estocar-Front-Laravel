<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Usuários e Cargos</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Grupo</th>
                    <th style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody id="users_body">
                <tr>
                    <td colspan="5" class="text-center text-muted">Carregando dados...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('js')
<script>
    const API_URL = 'http://estocar-1.test/api';
    const USERS_ENDPOINT = '/lista/users'; // Endpoint para buscar usuários
    const EDIT_URL_BASE = "{{ url('/users/editar') }}"; // Rota de edição de usuário
    const DELETE_URL_BASE = "/deletar/user"; // Endpoint base para deleção de usuário

    // Helper que adiciona o Authorization Header e trata erros 401/403.
    async function apiFetch(endpoint, options = {}) {
        const token = localStorage.getItem('api_token');
        const fetchUrl = `${API_URL}${endpoint}`;

        if (!token) {
            console.error("Token de API ausente.");
            // Redireciona para o login se não houver token
            window.location.href = '{{ route('login') }}';
            return Promise.reject(new Error("Token de autenticação ausente."));
        }

        const response = await fetch(fetchUrl, {
            // Correção: usa o spread operator para passar as opções corretamente
            ...options, 
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                // Permite sobrescrever cabeçalhos, se necessário (ex: Content-Type)
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

    // Função para Renderizar a Tabela de Usuários
    function renderUsersTable(users) {
        const body = document.getElementById('users_body');
        body.innerHTML = ''; // Limpa o "Carregando dados..."

        if (!users || users.length === 0) {
            body.innerHTML = `<tr><td colspan="5" class="text-center">Nenhum usuário encontrado.</td></tr>`;
            return;
        }

        users.forEach(user => {
            // LÓGICA DO GRUPO (CARGO)
            // Pega o nome do primeiro cargo do array 'cargos'
            const cargoName = user.cargos && user.cargos.length > 0 
                              ? user.cargos[0].Nome 
                              : 'Nenhum'; 

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.id}</td>
                <td>
                    <a href="${EDIT_URL_BASE}/${user.id}" class="text-primary">
                        ${user.name || 'N/A'}
                    </a>
                </td>
                <td>${user.email || 'N/A'}</td>
                <td>${cargoName}</td>
                <td>
                    <button class="btn btn-xs btn-info" onclick="viewUser(${user.id})" title="Editar">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-xs btn-danger" onclick="deleteUser(${user.id})" title="Deletar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            body.appendChild(row);
        });
    }

    // Função para Buscar Usuários
    async function fetchUsers() {
        try {
            const response = await apiFetch(USERS_ENDPOINT, { method: 'GET' });

            if (response.ok) {
                const data = await response.json();
                // Acessa 'data.users' conforme a estrutura de retorno da sua API
                renderUsersTable(data.users || []); 
            } else {
                document.getElementById('users_body').innerHTML = `<tr><td colspan="5" class="text-center text-danger">Erro ao carregar os usuários: ${response.statusText}</td></tr>`;
            }

        } catch (error) {
            console.error("Falha fatal no Fetch:", error);
            document.getElementById('users_body').innerHTML = `<tr><td colspan="5" class="text-center text-danger">Falha de comunicação com o servidor.</td></tr>`;
        }
    }

    // Inicialização
    document.addEventListener('DOMContentLoaded', fetchUsers);

    // Função para redirecionar para edição
    window.viewUser = (id) => {
        window.location.href = `${EDIT_URL_BASE}/${id}`;
    };

    // Função para deletar usuário
    window.deleteUser = async (id) => {
        if (!confirm(`Tem certeza que deseja deletar o usuário ID ${id}? Esta ação não pode ser desfeita.`)) {
            return;
        }

        const rowElement = document.querySelector(`[onclick="deleteUser(${id})"]`).closest('tr');
        rowElement.style.opacity = 0.5;

        const USER_DELETE_ENDPOINT = `${DELETE_URL_BASE}/${id}`; 

        try {
            const response = await apiFetch(USER_DELETE_ENDPOINT, {
                method: 'DELETE'
            });

            if (response.ok) {
                rowElement.remove();
                alert(`Usuário ID ${id} deletado com sucesso!`);
            } else {
                const errorData = await response.json().catch(() => ({
                    message: 'Erro desconhecido.'
                }));
                throw new Error(errorData.message || response.statusText);
            }
        } catch (error) {
            console.error("Falha ao deletar:", error);
            alert(`Falha ao deletar usuário ID ${id}: ${error.message}`);

            if (rowElement) {
                rowElement.style.opacity = 1;
            }
        }
    };
</script>
@endpush