<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserViewController extends Controller
{
    public function update(int $usuario)
    {
        return view('users.editar', [
            'usuarioID' => $usuario
        ]);
    }
}
