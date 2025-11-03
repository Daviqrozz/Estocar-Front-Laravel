<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarroViewController extends Controller
{
    public function update(int $carro)
    {
        return view('carros.editar',
            [
                'carroId' => $carro
            ]
        );
    }
}
