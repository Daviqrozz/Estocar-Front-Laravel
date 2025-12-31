<div class="row">
    <!-- Card: OS em andamento -->
    <div class="col-md-6 col-sm-12 col-12">
        <div class="small-box bg-warning">
            <div class="inner">
                <h4>Em andamento</h4>
                <h2 id="os_em_andamento_number">0</h2>
            </div>
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>

    <!-- Card: OS abertas -->
    <div class="col-md-6 col-sm-12 col-12">
        <div class="small-box bg-primary">
            <div class="inner">
                <h4>Abertas</h4>
                <h2 id="os_abertas_number">0</h2>
            </div>
            <div class="icon">
                <i class="fas fa-folder-open"></i>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
  const osEmAndamentoNumber = document.getElementById('os_em_andamento_number');
  const osAbertasNumber      = document.getElementById('os_abertas_number');

  const API_URL = 'http://estocar-1.test/api';
  const ORDENS_ENDPOINT = '/lista/ordens-servico';

  const token = localStorage.getItem('api_token');

  if (!token) {
    window.location.href = '/login';
  }

  async function apiFetch(endpoint, options = {}) {
    const fetchUrl = endpoint.startsWith(API_URL) ? endpoint : `${API_URL}${endpoint}`;

    const defaultHeaders = {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`
    };

    const response = await fetch(fetchUrl, {
      ...options,
      headers: {
        ...defaultHeaders,
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

  async function loadOrdensServico() {
    try {
      const response = await apiFetch(ORDENS_ENDPOINT, { method: 'GET' });

      if (!response.ok) {
        console.error('Erro ao carregar OS:', response.status, response.statusText);
        return;
      }

      const data = await response.json();

      const ordens = Array.isArray(data.ordens_servico) ? data.ordens_servico : [];

      let abertas = 0;
      let andamento = 0;

      ordens.forEach(os => {
        if (os.status === 0) {
          abertas++;
        } else if (os.status === 1) {
          andamento++;
        }
      });

      if (osEmAndamentoNumber) {
        osEmAndamentoNumber.textContent = andamento;
      }

      if (osAbertasNumber) {
        osAbertasNumber.textContent = abertas;
      }

    } catch (error) {
      console.error('Falha ao carregar OS:', error.message);
    }
  }

  document.addEventListener('DOMContentLoaded', loadOrdensServico);
</script>
@endpush
