<div class="row">
    <!-- Atalho: Carros -->
    <div class="col-md-3 col-sm-6 col-12">
        <a href="#" class="small-box bg-primary">
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
        <a href="#" class="small-box bg-success">
            <div class="inner">
                <h5>Clientes atendidos</h5>
                <h4 class="">54</h4>
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
            <span class="info-box-number">12</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>
@push('css')
    <style>
        .small-box {

            min-height: 90px;
            padding-bottom: 40%;
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

        const API_URL = 'http://estocar-1.test/api';
        const CARROS_ENDPOINT = '/lista/carros'

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
            // Alterado 'absolute' para 'block' para controle de visibilidade
            if (carro_shortcut_spinner) carro_shortcut_spinner.style.display = 'block'
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

        document.addEventListener('DOMContentLoaded', loadCars);
    </script>
@endpush
