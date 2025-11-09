<div class="row">
    <!-- Atalho: Carros -->
    <div class="col-md-3 col-sm-6 col-12">
        <a href="{{ route('carros.carros') }}" class="small-box bg-primary">
            <div class="inner">
                <h5>Carros em estoque</h5>
                <h4 id="car_stock_shortcut">
                    <i id="car_stock_spinner" class="fas fa-spinner fa-spin"></i>
                    <h4 id="car_stock_number"></h4>
                </h4>

            </div>
            <div class="icon">
                <i class="fas fa-car"></i>
            </div>
        </a>
    </div>

    <!-- Atalho: Clientes -->

    <div class="col-md-3 col-sm-6 col-12">
        <a href="{{ route('clientes.clientes') }}" class="small-box bg-success">
            <div class="inner">
                <h5>Clientes atendidos</h5>
                <h4 id="clientes_stock_shortcut">
                    <i id="clientes_stock_spinner" class="fas fa-spinner fa-spin"></i>
                    <h4 id="clientes_stock_number"></h4>
                </h4>

            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </a>
    </div>

    <!-- Atalho: Relatórios -->

    <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Movimentação do mês</span>
            <span class="info-box-number"></span>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>
@push('css')
    <style>
        .small-box {

            min-height: 90px;
            padding-bottom: 0%;
            height: 0;
            position: relative;
        }


        .small-box .inner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 10px;
        }
    </style>
@endpush

@push('js')
    <script>
        const carro_shortcut_number = document.getElementById('car_stock_number')
        const carro_shortcut_spinner = document.getElementById('car_stock_spinner')
        const clientes_shortcut_number = document.getElementById('clientes_stock_number')
        const clientes_shortcut_spinner = document.getElementById('clientes_stock_spinner')
        const total_mov_number = document.getElementById('info-box-number')

        const API_URL = 'http://estocar-1.test/api';
        const CARROS_ENDPOINT = '/lista/carros'
        const CLIENTES_ENDPOINT = '/lista/clientes'

        const token = localStorage.getItem('api_token')

        if (!token) {
            window.location.href = '{{ route('login') }}';
        }

        async function apiFetch(endpoint, options = {}) {
            const fetchUrl = endpoint.startsWith(API_URL) ? endpoint : `${API_URL}${endpoint}`;

            if (!token) {
                return Promise.reject(new Error("Token de autenticação ausente."));
            }

            const defaultHeaders = {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }

            const response = await fetch(fetchUrl, {
                ...options,
                headers: {
                    ...defaultHeaders,
                    ...(options.headers || {})
                }
            })
            if (response.status === 401 || response.status === 403) {
                localStorage.removeItem('api_token')
                window.location.href = '{{ route('login') }}';
                return Promise.reject(new Error("Não autorizado."));
            }

            return response;
        }

        async function loadCars() {

            if (carro_shortcut_spinner) carro_shortcut_spinner.style.display = 'absolute'
            if (carro_shortcut_number) carro_shortcut_number.style.display = 'none'

            try {
                const response = await apiFetch(CARROS_ENDPOINT, {
                    method: 'GET'
                });

                if (response.ok) {
                    const data = await response.json()
                    const carros = data.carros
                    const totalCarros = Array.isArray(carros) ? carros.length : 0;

                    if (carro_shortcut_number) {
                        carro_shortcut_number.innerText = totalCarros
                        carro_shortcut_number.style.display = 'block';
                    }
                } else {
                    console.error(`Erro HTTP ao carregar carros: ${response.status} ${response.statusText}`);
                    if (carro_shortcut_number) {
                        carro_shortcut_number.innerText = response.status;
                        carro_shortcut_number.style.display = 'block';
                    }
                }
            } catch (error) {
                console.error("Falha na operação de carregamento:", error.message);
                if (carro_shortcut_number) {
                    carro_shortcut_number.innerText = 'ERRO';
                    carro_shortcut_number.style.display = 'block';
                }
            } finally {
                if (carro_shortcut_spinner) carro_shortcut_spinner.style.display = 'none';
            }
        }

        async function loadClientes() {

            if (clientes_shortcut_spinner) clientes_shortcut_spinner.style.display = 'absolute'
            if (clientes_shortcut_spinner) clientes_shortcut_spinner.style.display = 'none'

            try {
                const response = await apiFetch(CLIENTES_ENDPOINT, {
                    method: 'GET'
                });

                if (response.ok) {
                    const data = await response.json()
                    const clientes = data.clientes;
                    const totalClientes = Array.isArray(clientes) ? clientes.length : 0

                    if (clientes_shortcut_number) {
                        clientes_shortcut_number.innerText = totalClientes
                        clientes_shortcut_number.style.display = 'block'
                    } else {
                        console.error(`Erro HTTP ao carregar carros: ${response.status} ${response.statusText}`);
                        clientes_shortcut_number.innerText = response.status
                        clientes_shortcut_number.style.display = 'block'
                    }
                }
            } catch (error) {
                console.error("Falha na operação de carregamento:", error.message)
                if (clientes_shortcut_number) {
                    clientes_shortcut_number.innerText = 'ERRO';
                    clientes_shortcut_number.style.display = 'block';
                }
            } finally {
                if (clientes_shortcut_spinner) clientes_shortcut_spinner.style.display = 'none';
                
            }

        }
    
        document.addEventListener('DOMContentLoaded', () => {
            loadCars();
            loadClientes();
        });
    
    </script>
@endpush
