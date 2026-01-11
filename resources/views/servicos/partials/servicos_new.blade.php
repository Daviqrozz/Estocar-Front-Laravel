<div class="card">
    <div class="card-header bg-success">
        <h3 class="card-title">
            <i class="fas fa-plus-circle"></i> Nova Ordem de Serviço
        </h3>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                <!-- Cliente -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <select name="cliente_select" id="cliente_select" class="form-control" required>
                            <option value="cliente_default">Selecione um cliente...</option>
                        </select>
                    </div>
                </div>

                <!-- Carro -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="carro_id">Carro</label>
                        <select name="carro_default" id="carro_default" class="form-control" required>
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
                            <option value="aberta">Aberta</option>
                            <option value="em_andamento">Em andamento</option>
                            <option value="concluida">Concluída</option>
                        </select>
                    </div>
                </div>

                <!-- Data -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="data_abertura">Data</label>
                        <input type="date" name="data_abertura" id="data_abertura" class="form-control" required>
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
    </div>
</div>
@push('js')
    <script>
        const API_URL = 'http://estocar-1.test/api'
        CONST REL_CREATE_ENDPOINT = '/criar/relatorio'

        async function apiFetch(endpoint, options = {}) {
            const token = localStorage.getItem('api_token')
            
            if (!token) {
                console.error('Token de Api Ausente')
                return Promise.reject(new Error("Token de autenticação ausente."));
            }
        }
    </script>
@endpush
