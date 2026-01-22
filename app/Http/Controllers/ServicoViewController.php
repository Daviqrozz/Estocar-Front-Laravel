<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicoViewController extends Controller
{
    public function update(int $servico){
        return view('servicos.editar',[
            'servicoID' => $servico
        ]);
    }
}
