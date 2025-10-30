<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller genérico usado exclusivamente para renderizar views.
 *
 * Em uma arquitetura Server de Templates (Blade) + Lógica Client-Side (JavaScript),
 * este Controller serve apenas como o ponto de ancoragem para as rotas,
 * sem nenhuma lógica de aplicação ou autenticação.
 */
class ViewController extends Controller
{
    /**
     * Renderiza a view Blade especificada pelo parâmetro 'viewName' na rota.
     * * @param string $viewName O nome da view a ser renderizada (ex: 'home', 'carros.criar').
     * @return \Illuminate\View\View
     */
    public function render(string $viewName)
    {
        // Simplesmente retorna a view.
        // Toda a complexidade (autenticação, fetch de dados) está no JavaScript.
        return view($viewName);
    }
}
