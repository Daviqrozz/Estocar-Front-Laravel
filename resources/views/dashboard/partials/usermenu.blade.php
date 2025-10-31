{{-- Este é o menu de usuário da barra de navegação (navbar) --}}
<li class="nav-item dropdown user-menu">
    {{-- Link visível na navbar --}}
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}"
             class="user-image img-circle elevation-2" alt="User Image">
        {{-- Span que será atualizado pelo JS na dashboard.blade.php --}}
        <span class="d-none d-md-inline" id="navbar-user-name">Carregando...</span> 
    </a>
    
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- Cabeçalho do Dropdown -->
        <li class="user-header bg-primary">
            <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}"
                 class="img-circle elevation-2" alt="User Image">
            <p>
                {{-- Elementos para exibir nome e email --}}
                <span id="header-user-name">Carregando Nome</span> - <span id="header-user-role">Administrador</span>
                <small id="header-user-email">email@exemplo.com</small>
            </p>
        </li>

        <!-- Corpo opcional -->
        <li class="user-body">
            <div class="row">
                <div class="col-12 text-center">
                    <a href="#">Meu Perfil</a>
                </div>
            </div>
        </li>

        <!-- Rodapé (Logout) -->
        <li class="user-footer">
            <a href="#" class="btn btn-default btn-flat">Perfil</a>
            {{-- CHAMA A FUNÇÃO GLOBAL DE LOGOUT --}}
            <a href="#" class="btn btn-default btn-flat float-right" 
               onclick="event.preventDefault(); window.logout();">
                Sair
            </a>
        </li>
    </ul>
</li>
