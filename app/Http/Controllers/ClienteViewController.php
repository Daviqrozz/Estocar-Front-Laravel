<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteViewController extends Controller
{
    public function update(int $cliente)
    {
        return view('clientes.editar', [

            'clienteID' => $cliente

        ]);
    }
}
