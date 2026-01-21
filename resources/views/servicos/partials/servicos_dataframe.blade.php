<div style="max-height: 450px; overflow-y: auto;">
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>#OS</th>
                <th>Cliente</th>
                <th>Carro</th>
                <th>Serviço</th>
                <th>Valor total</th>
                <th>Status</th>
                <th>Data abertura</th>
                <th style="width: 100px">Ações</th>
            </tr>
        </thead>
        <tbody id="tabela-os-body">

        </tbody>
    </table>
</div>

@push('js')
    <script>
        const tbodyOs = document.getElementById('tabela-os-body');

        if (!token) {
            window.location.href = '/login';
        }

        function mapStatus(status) {
            if (status === 0) return {
                label: 'Aberta',
                classe: 'badge badge-primary'
            };
            if (status === 1) return {
                label: 'Em andamento',
                classe: 'badge badge-warning'
            };
            if (status === 2) return {
                label: 'Concluída',
                classe: 'badge badge-success'
            };
            return {
                label: 'Desconhecido',
                classe: 'badge badge-secondary'
            };
        }

        async function apiFetch(endpoint, options = {}) {
            const url = endpoint.startsWith(API_URL) ? endpoint : `${API_URL}${endpoint}`;

            const response = await fetch(url, {
                ...options,
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    ...(options.headers || {})
                }
            });

            if (response.status === 401 || response.status === 403) {
                localStorage.removeItem('api_token');
                window.location.href = '/login';
                throw new Error('Não autorizado');
            }

            return response;
        }

        function formatarData(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            if (Number.isNaN(d.getTime())) return '-';
            return d.toLocaleDateString('pt-BR');
        }

        function formatarValor(valor) {
            if (valor === null || valor === undefined) return 'R$ 0,00';
            const num = Number(valor);
            if (Number.isNaN(num)) return 'R$ 0,00';
            return num.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        async function deleteOs(id) {
            if (!confirm('Deseja realmente excluir esta OS?')) return;

            try {
                const response = await apiFetch(`/deletar/ordem-servico/${id}`, {
                    method: 'DELETE',
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Erro ao deletar OS.');
                }

                alert(`OS ${id} deletada com sucesso!`);
                await carregarTabelaOS(); // recarrega os dados, igual fetchVendas()

            } catch (error) {
                console.error('Falha ao deletar OS:', error);
                alert(`Erro ao deletar: ${error.message}`);
            }
        }


        async function carregarTabelaOS() {
            try {
                const response = await apiFetch(ORDENS_ENDPOINT, {
                    method: 'GET'
                });

                if (!response.ok) {
                    console.error('Erro ao carregar OS:', response.status, response.statusText);
                    return;
                }

                const data = await response.json();
                const ordens = Array.isArray(data.ordens_servico) ? data.ordens_servico : [];

                // Limpa o tbody antes de preencher
                tbodyOs.innerHTML = '';

                if (ordens.length === 0) {
                    tbodyOs.innerHTML = `
        <tr>
          <td colspan="8" class="text-center text-muted">Nenhuma ordem de serviço encontrada.</td>
        </tr>`;
                    return;
                }

                ordens.forEach(os => {
                    const tr = document.createElement('tr');

                    const statusInfo = mapStatus(os.status);

                    const clienteNome = os.cliente && os.cliente.nome ? os.cliente.nome : '-';
                    const carroModelo = os.carro && os.carro.modelo ? os.carro.modelo : '';
                    const carroAno = os.carro && os.carro.ano ? os.carro.ano : '';
                    const carroTexto = carroModelo ? `${carroModelo} ${carroAno}`.trim() : '-'

                    let servicoNome = '-';
                    if (Array.isArray(os.registros) && os.registros.length > 0) {
                        const reg = os.registros[0];
                        if (reg.servico && reg.servico.nome) {
                            servicoNome = reg.servico.nome.trim();
                        }
                    }

                    const valorTotal = formatarValor(os.valor_total);
                    const dataAbertura = formatarData(os.data_abertura);

                    tr.innerHTML = `
        <td>#${os.id}</td>
        <td>${clienteNome}</td>
        <td>${carroTexto}</td>
        <td>${servicoNome}</td>
        <td>${valorTotal}</td>
        <td><span class="${statusInfo.classe}">${statusInfo.label}</span></td>
        <td>${dataAbertura}</td>
        <td>
          <button class="btn btn-xs btn-info">
                        <i class="fas fa-pen"></i>
                    </button>
          <button class="btn btn-xs btn-danger" onclick="deleteOs(${os.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-xs btn-primary">
                        <i class="fas fa-eye"></i>
                    </button>
                    
        </td>
      `;

                    tbodyOs.appendChild(tr);
                });

            } catch (error) {
                console.error('Falha ao carregar OS:', error.message);
                tbodyOs.innerHTML = `
      <tr>
        <td colspan="8" class="text-center text-danger">Erro ao carregar ordens de serviço.</td>
      </tr>`;
            }
        }

        document.addEventListener('DOMContentLoaded', carregarTabelaOS);
    </script>
@endpush
