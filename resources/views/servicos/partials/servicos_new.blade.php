<div class="card">
    <div class="card-header bg-success">
        <h3 class="card-title">
            <i class="fas fa-plus-circle"></i> Nova Ordem de Serviço
        </h3>
    </div>
    <div class="card-body">
        <form id="create_os_form" method="POST">
            <div class="row">
                <!-- Cliente -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select name="cliente_select" id="cliente_select" class="form-control" required>
                            <option value="cliente_default" id="cliente_default_option">Selecione um cliente...</option>
                        </select>
                    </div>
                </div>

                <!-- Carro -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="carro_id">Carro</label>
                        <select name="carro_select" id="carro_select" class="form-control" required>
                            <option value="">Selecione um carro...</option>
                        </select>
                    </div>
                </div>

                <!-- Serviços -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="servico_default">Serviço</label>
                        <select name="servico_select" id="servico_select" class="form-control">
                            <option value="">Selecione um serviço...</option>
                        </select>
                    </div>
                </div>

                <!-- Valor Total -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="valor_total">Valor Total</label>
                        <input type="number" name="valor_total" id="valor_total" class="form-control"
                            placeholder="R$ 0,00" step="0.01" min="0" required>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="0">Aberta</option>
                            <option value="1">Em andamento</option>
                            <option value="2">Concluída</option>
                        </select>
                    </div>
                </div>

                <!-- Data -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="data_abertura">Data</label>
                        <input type="date" name="data_abertura" id="data_abertura" class="form-control">
                        <span>(Caso nao preencha,a data atual sera inserida)</span>
                    </div>

                </div>

                <!-- Observações -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea name="observacoes" id="observacoes" class="form-control" rows="3"
                            placeholder="Descreva os serviços ou observações..."></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Abrir OS
            </button>
        </form>
        <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>
    </div>
</div>
@push('js')
    <script>
        const CREATE_OS_ENDPOINT = '/criar/ordem-servico'
        const CLIENTE_FETCH_ENDPOINT = '/lista/clientes'
        const SERVICO_FETCH_ENDPOINT = '/lista/servicos'
        const CARRO_FETCH_ENDPOINT = '/lista/carros'


        async function apiFetch(endpoint, options = {}) {
            const token = localStorage.getItem('api_token');
            const fetchUrl = endpoint.startsWith(API_URL) ? endpoint : `${API_URL}${endpoint}`;

            if (!token) {
                console.error("Token de API ausente.");
                return Promise.reject(new Error("Token de autenticação ausente."));
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
                localStorage.removeItem('api_token');
                window.location.href = '{{ route('login') }}';
                return Promise.reject(new Error("Não autorizado."));
            }

            return response;
        }

        // Carrega clientes
        async function loadClientes() {
            try {
                const response = await apiFetch(CLIENTE_FETCH_ENDPOINT, {
                    method: 'GET'
                })

                if (response.ok) {
                    const data = await response.json()
                    const clientes = data.clientes

                    const cliente_select = document.getElementById('cliente_select')

                    clientes.forEach(cliente => {
                        const cliente_option = document.createElement('option')

                        cliente_option.textContent = `${cliente.id} - ${cliente.nome}`

                        cliente_option.value = `${cliente.id}`

                        cliente_select.appendChild(cliente_option)

                    });
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar dado: ${response.statusText}`);
                }
            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                const cliente_default_option = document.getElementById('cliente_default_option')

                cliente_default_option.value = 'Erro ao carregar Dados'
            }
        }

        async function loadServicos() {
            try {
                const response = await apiFetch(SERVICO_FETCH_ENDPOINT, {
                    method: 'GET'
                })

                if (response.ok) {
                    const servicos = await response.json()

                    const servico_select = document.getElementById('servico_select')

                    servicos.forEach(servico => {
                        const servico_option = document.createElement('option')

                        servico_option.textContent = `${servico.id} - ${servico.nome}`

                        servico_option.value = `${servico.id}`

                        servico_select.appendChild(servico_option)

                    });
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar dado: ${response.statusText}`);
                }
            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                const servico_default_option = document.getElementById('servico_default_option')

                servico_default_option.value = 'Erro ao carregar Dados'
            }
        }


        async function loadCarros() {
            try {

                const response = await apiFetch(CARRO_FETCH_ENDPOINT, {
                    method: 'GET'
                })

                if (response.ok) {
                    const data = await response.json()
                    if (data.carros.status == 1) {

                    }

                    const carros = data.carros

                    const carro_select = document.getElementById('carro_select')

                    carros.filter(carro => carro.status === 1).forEach(carro => {
                        const carro_option = document.createElement('option')
                        carro_option.textContent = `${carro.id} - ${carro.marca} ${carro.modelo}`

                        carro_option.value = `${carro.id}`

                        carro_select.appendChild(carro_option)

                    });
                } else {

                    const errorData = await response.json();
                    throw new Error(errorData.message || `Erro ao buscar dado: ${response.statusText}`);
                }

            } catch (error) {
                console.error("Falha ao carregar os dados:", error);
                const carro_default_option = document.getElementById('carro_default_option')

                carro_default_option.value = 'Erro ao carregar Dados'
            }
        }


        // Salvar alterações
        async function handleSaveFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('create_os_form');
            const submitButton = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('error-message');

            errorBox.style.display = 'none';
            submitButton.disabled = true;
            submitButton.innerText = 'Salvando...';

            if (
                !form.elements['cliente_select'].value ||
                form.elements['cliente_select'].value === 'cliente_default' ||
                !form.elements['carro_select'].value ||
                !form.elements['servico_select'].value
            ) {
                errorBox.innerText = 'Selecione um cliente,carro ou servico válidos.';
                errorBox.style.display = 'block';
                submitButton.disabled = false;
                submitButton.innerText = 'Salvar Alterações';
                return;
            }
            const createData = {
                cliente_id: parseInt(form.elements['cliente_select'].value),
                carro_id: parseInt(form.elements['carro_select'].value),
                servico_id: parseInt(form.elements['servico_select'].value),
                data_abertura: form.elements['data_abertura'].value,
                valor_total: parseFloat(form.elements['valor_total'].value),
                status: parseInt(form.elements['status'].value),
                descricao: form.elements['observacoes'].value,
            };



            try {
                const body = JSON.stringify(createData);
                const response = await apiFetch(CREATE_OS_ENDPOINT, {
                    method: 'POST',
                    body,
                    headers:{'Content-Type' : 'application/json'}
                });

                if (response.ok) {
                    window.location.href = '{{ route('servicos.servicos') }}';
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Erro desconhecido ao salvar.');
                }
            } catch (error) {
                console.error("Falha ao salvar:", error);
                errorBox.innerText = `Erro ao salvar: ${error.message}`;
                errorBox.style.display = 'block';
            } finally {
                submitButton.disabled = false;
                submitButton.innerText = 'Salvar Alterações';
            }
        }
        
        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            loadClientes()
            loadCarros()
            loadServicos()
            document
                .getElementById('create_os_form').addEventListener('submit', handleSaveFormSubmit);
        });
    </script>
@endpush
