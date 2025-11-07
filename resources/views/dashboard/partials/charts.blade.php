<div class="card bg-grey ">
              <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Movimentação de Veiculos
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <canvas class="chart chartjs-render-monitor" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 330px;" width="412" height="312"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>


    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('line-chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                    datasets: [{
                        label: 'Movimentação de Veiculos',
                        data: [12, 19, 3, 5, 2, 3],
                        fill: false,
                        backgroundColor: 'rgba(13, 122, 253, 1)',
                        borderColor: 'grey',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
        </script>
    @stop
