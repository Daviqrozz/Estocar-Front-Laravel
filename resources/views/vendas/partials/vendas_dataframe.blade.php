@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Transações</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Carro Vendido</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody id="vendas_body">
                <tr>
                    <td colspan="8" class="text-center text-muted">Carregando dados...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('js')
<script>

    const API_URL = 'http://estocar-1.test/api';
    const VENDAS_ENDPOINT = '/lista/vendas';

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

        // Tratamento de falha de autenticação
        if (response.status === 401 || response.status === 403) {
            console.error("Autenticação falhou. Token inválido/expirado.");
            localStorage.removeItem('api_token');
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
            case '1':
                select.style.backgroundColor = '#28a745'; // Verde - Paga
                break;
            case '0':
                select.style.backgroundColor = '#ffc107'; // Amarelo - Pendente
                break;
            case '2':
                select.style.backgroundColor = '#dc3545'; // Vermelho - Cancelada
                break;
            default:
                select.style.backgroundColor = '#007bff';
        }
    }

    function renderVendasTable(vendas) {
        const body = document.getElementById('vendas_body');
        body.innerHTML = '';

        vendas.forEach(venda => {
            const statusOptions = `
                <option value="1" ${venda.status === 1 ? 'selected' : ''}>Paga</option>
                <option value="0" ${venda.status === 0 ? 'selected' : ''}>Pendente</option>
                <option value="2" ${venda.status === 2 ? 'selected' : ''}>Cancelada</option>
            `;

            const valorFormatado = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(venda.valor_venda || 0);

            const dataFormatada = new Date(venda.data_venda).toLocaleDateString('pt-BR');

            // Monta a linha da tabela
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${venda.id}</td>
                <td>
                    <a href="{{ url('/vendas/editar') }}/${venda.id}" class="text-primary">
                        ${venda.carro?.marca || 'N/A'} ${venda.carro?.modelo || ''}
                    </a>
                </td>
                <td>${venda.cliente?.nome || 'N/A'}</td>
                <td>${venda.user?.name || '—'}</td>
                <td>${dataFormatada}</td>
                <td>${valorFormatado}</td>
                <td>
                    <select class="status-select form-control form-control-sm text-white font-weight-bold" data-venda-id="${venda.id}">
                        ${statusOptions}
                    </select>
                </td>
                <td>
                    <button class="btn btn-xs btn-info" onclick="editarVenda(${venda.id})">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-xs btn-danger" onclick="deletarVenda(${venda.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            body.appendChild(row);

            // Inicializa estilo do select
            const newSelect = row.querySelector('.status-select');
            updateSelectColor(newSelect);
            newSelect.addEventListener('change', (e) => {
                updateSelectColor(newSelect);
                updateStatusVenda(venda.id, e.target.value);
            });
        });
    }

    async function fetchVendas() {
        try {
            const response = await apiFetch(VENDAS_ENDPOINT, { method: 'GET' });

            if (response.ok) {
                const data = await response.json();
                console.log(data.vendas)
                renderVendasTable(data.vendas || data);
            
            } else {
                document.getElementById('vendas_body').innerHTML = `<tr><td colspan="8" class="text-center text-danger">Erro ao carregar as vendas: ${response.statusText}</td></tr>`;
            }
        } catch (error) {
            console.error("Falha fatal no Fetch:", error);
            document.getElementById('vendas_body').innerHTML = `<tr><td colspan="8" class="text-center text-danger">Falha de comunicação com o servidor.</td></tr>`;
        }
    }

    document.addEventListener('DOMContentLoaded', fetchVendas);

    const EDIT_URL_BASE = "{{ url('/vendas/editar') }}";

    window.editarVenda = (id) => {
        window.location.href = `${EDIT_URL_BASE}/${id}`;
    };

    window.updateStatusVenda = async (id, novoStatus) => {
        try {
            const response = await apiFetch(`/editar/venda/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: novoStatus })
            });

            if (response.ok) {
                console.log(`Venda ${id} atualizada para status ${novoStatus}`);
            } else {
                const errorData = await response.json().catch(() => ({ message: 'Erro desconhecido.' }));
                throw new Error(errorData.message || response.statusText);
            }
        } catch (error) {
            console.error("Falha ao atualizar status:", error);
            alert(`Falha ao atualizar venda: ${error.message}`);
            fetchVendas();
        }
    };

    window.deletarVenda = async (id) => {
        if (!confirm(`Tem certeza que deseja deletar a venda ${id}? Esta ação não pode ser desfeita.`)) {
            return;
        }

        const rowElement = document.querySelector(`[onclick="deletarVenda(${id})"]`).closest('tr');
        const originalHtml = rowElement.innerHTML;
        rowElement.style.opacity = 0.5;

        try {
            const response = await apiFetch(`/deletar/venda/${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                rowElement.remove();
                alert(`Venda ID ${id} deletada com sucesso!`);
            } else {
                const errorData = await response.json().catch(() => ({ message: 'Erro desconhecido.' }));
                throw new Error(errorData.message || response.statusText);
            }
        } catch (error) {
            console.error("Falha ao deletar:", error);
            alert(`Falha ao deletar venda ID ${id}: ${error.message}`);

            if (rowElement) {
                rowElement.style.opacity = 1;
            }
        }
    };

</script>
@endpush