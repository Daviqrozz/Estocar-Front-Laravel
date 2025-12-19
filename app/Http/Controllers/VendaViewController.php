<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendaViewController extends Controller
{
    public function update(int $venda)
    {
        return view('vendas.editar', [
            'vendaID' => $venda
        ]);
    }
}
