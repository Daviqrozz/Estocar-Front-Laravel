<div class="card">
<div class="d-flex justify-content-between align-items-center card-header w-100">
    
    <h3 class="card-title mb-0">Lista de veículos cadastrados</h3> 
     
    <div class="card-date">
        <span>Ultima atualização: 10/18/2025 19:43</span>
    </div>
    <div class="l-100% d-flex align-items-center ms-auto"> 
        <label for="car_searchbar" class="mr-1 mb-0">Pesquisar: </label>
        <input type="text" name="search" placeholder="Marca,modelo,ano,cor" id="car_searchbar">
    </div>
</div>

    <div class="card-body">
        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca / Modelo</th>
                    <th>Ano</th>
                    <th>Placa</th>
                    <th>Status</th>
                    <th>Valor Fipe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Volkswagen Gol 1.6</td>
                    <td>2018</td>
                    <td>ABC-1234</td>
                    <td><span class="badge bg-success">Disponível</span></td>
                    <td>R$ 45.900</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Chevrolet Onix LT 1.4</td>
                    <td>2020</td>
                    <td>DEF-5678</td>
                    <td><span class="badge bg-warning">Em revisão</span></td>
                    <td>R$ 68.300</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Honda Civic EXL 2.0</td>
                    <td>2019</td>
                    <td>GHI-9012</td>
                    <td><span class="badge bg-success">Disponível</span></td>
                    <td>R$ 108.000</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Fiat Strada Freedom 1.3</td>
                    <td>2021</td>
                    <td>JKL-3456</td>
                    <td><span class="badge bg-danger">Vendido</span></td>
                    <td>R$ 89.700</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Renault Duster 1.6</td>
                    <td>2017</td>
                    <td>MNO-7890</td>
                    <td><span class="badge bg-secondary">Reservado</span></td>
                    <td>R$ 59.800</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Toyota Corolla XEi 2.0</td>
                    <td>2022</td>
                    <td>PQR-1235</td>
                    <td><span class="badge bg-success">Disponível</span></td>
                    <td>R$ 132.500</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Hyundai HB20 Comfort 1.0</td>
                    <td>2019</td>
                    <td>STU-6789</td>
                    <td><span class="badge bg-danger">Vendido</span></td>
                    <td>R$ 58.400</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Ford Ka SE 1.0</td>
                    <td>2018</td>
                    <td>VWX-2468</td>
                    <td><span class="badge bg-warning">Em revisão</span></td>
                    <td>R$ 47.600</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-1">
            <Label for="count-select">Mostrar:</Label>
                <select name="count" id="count-select">
                    <option value="10">10</option>
                    <option value="40">40</option>
                    <option value="80">80</option>
                    <option value="120">120</option>
                </select>
        </div>
    </div>
</div>