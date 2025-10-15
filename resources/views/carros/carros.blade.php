@extends('adminlte::page')

@section('title', 'Carros')

@section('content_header')
<div class="d-flex justify-content-between">
    <h2>Carros cadastrados na plataforma</h2>

    <button type="button" class="btn btn-primary">Cadastrar</button>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">

                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Buscar">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 350px;">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Marca - Modelo</th>
                            <th>Ano</th>
                            <th>Placa</th>
                            <th>Status</th>
                            <th>Valor Fipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>101</td>
                            <td>Chevrolet Onix 1.0 LT</td>
                            <td>2022</td>
                            <td>ABC-1A23</td>
                            <td><span class="badge bg-success">Disponível</span></td>
                            <td>R$ 68.500</td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>Volkswagen Gol 1.6</td>
                            <td>2020</td>
                            <td>BDF-4C56</td>
                            <td><span class="badge bg-warning">Reservado</span></td>
                            <td>R$ 52.900</td>
                        </tr>
                        <tr>
                            <td>103</td>
                            <td>Honda Civic EX 2.0</td>
                            <td>2021</td>
                            <td>CCD-8E22</td>
                            <td><span class="badge bg-danger">Vendido</span></td>
                            <td>R$ 118.000</td>
                        </tr>
                        <tr>
                            <td>104</td>
                            <td>Fiat Toro Freedom 2.0</td>
                            <td>2023</td>
                            <td>EEF-2G98</td>
                            <td><span class="badge bg-success">Disponível</span></td>
                            <td>R$ 159.900</td>
                        </tr>
                        <tr>
                            <td>105</td>
                            <td>Renault Kwid Zen 1.0</td>
                            <td>2022</td>
                            <td>FGH-3J76</td>
                            <td><span class="badge bg-success">Disponível</span></td>
                            <td>R$ 59.400</td>
                        </tr>
                        <tr>
                            <td>106</td>
                            <td>Toyota Corolla Cross XRE</td>
                            <td>2023</td>
                            <td>HIJ-9K34</td>
                            <td><span class="badge bg-warning">Em revisão</span></td>
                            <td>R$ 189.000</td>
                        </tr>
                        <tr>
                            <td>107</td>
                            <td>Jeep Compass Longitude 1.3</td>
                            <td>2024</td>
                            <td>JKL-0M89</td>
                            <td><span class="badge bg-success">Disponível</span></td>
                            <td>R$ 182.500</td>
                        </tr>
                        <tr>
                            <td>108</td>
                            <td>Hyundai HB20 Comfort 1.0</td>
                            <td>2021</td>
                            <td>MNO-6P54</td>
                            <td><span class="badge bg-danger">Vendido</span></td>
                            <td>R$ 68.900</td>
                        </tr>
                    </tbody>
                </table>
            </div>
       
        </div>
      
    </div>
</div>
@endsection
