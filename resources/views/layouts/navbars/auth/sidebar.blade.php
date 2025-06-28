<nav id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-64 flex flex-col bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-lg">
    <div id="sidebar_content" class="flex-1 flex flex-col">
            <div id="user">
                @if(Auth::check())
                    <img
                    src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://www.gravatar.com/avatar/' . md5(Auth::user()->email) . '?d=identicon' }}"
                    id="user_avatar"
                        alt="Avatar"
                    >
                    <p id="user_infos">
                        <span class="item-description user-name">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="item-description">
                            {{ Auth::user()->about_me ?? Auth::user()->email }}
                        </span>
                    </p>
                @else
                    <img src="{{ asset('images/avatar-default.png') }}" id="user_avatar" alt="Avatar">
                    <p id="user_infos">
                        <span class="item-description user-name">Visitante</span>
                        <span class="item-description">Faça login</span>
                    </p>
                @endif
            </div>
    
            <ul id="side_items">
                <li class="side-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ url('dashboard') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="item-description">
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('profile') ? 'active' : '' }}">
                    <a href="{{ url('profile') }}">
                        <i class="fa-solid fa-user"></i>
                        <span class="item-description">
                            Perfil
                        </span>
                    </a>
                </li>
                @if (auth()->user() && auth()->user()->role_id == 1)
                <li class="side-item {{ Request::is('user-management') ? 'active' : '' }}">
                    <a href="{{ url('user-management') }}">
                        <i class="fa-solid fa-users-gear"></i>
                        <span class="item-description">
                            Gerenciamento
                        </span>
                    </a>
                </li>
                @endif
                <li class="side-item {{ Request::is('banks') ? 'active' : '' }}">
                    <a href="{{ url('banks') }}">
                        <i class="fa-solid fa-building-columns"></i>
                        <span class="item-description">
                            Bancos
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('cashbook') ? 'active' : '' }}">
                    <a href="{{ url('cashbook') }}">
                        <i class="fa-solid fa-book"></i>
                        <span class="item-description">
                            Livro Caixa
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('cofrinho') ? 'active' : '' }}">
                    <a href="{{ url('cofrinho') }}">
                        <i class="fa-solid fa-piggy-bank"></i>
                        <span class="item-description">
                            Cofrinhos
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('products') ? 'active' : '' }}">
                    <a href="{{ url('products') }}">
                        <i class="fa-solid fa-box"></i>
                        <span class="item-description">
                            Produtos
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('clients') ? 'active' : '' }}">
                    <a href="{{ url('clients') }}">
                        <i class="fa-solid fa-users"></i>
                        <span class="item-description">
                            Clientes
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('sales') ? 'active' : '' }}">
                    <a href="{{ url('sales') }}">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="item-description">
                            Vendas
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('categories') ? 'active' : '' }}">
                    <a href="{{ url('categories') }}">
                        <i class="fa-solid fa-tags"></i>
                        <span class="item-description">
                            Categorias
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('dashboard/cashbook') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/cashbook') }}">
                        <i class="fa-solid fa-wallet"></i>
                        <span class="item-description">
                            Dashboard Cashbook
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('dashboard/sales') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/sales') }}">
                        <i class="fa-solid fa-chart-bar"></i>
                        <span class="item-description">
                            Dashboard Vendas
                        </span>
                    </a>
                </li>
                <li class="side-item {{ Request::is('dashboard/products') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/products') }}">
                        <i class="fa-solid fa-box-open"></i>
                        <span class="item-description">
                            Dashboard Produtos
                        </span>
                    </a>
                </li>
            </ul>
    
              <button id="open_btn">
                <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div id="logout">
            <a href="{{ url('/logout') }}" id="logout_btn" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition rounded-xl" role="menuitem">
                <i class="fa-solid fa-right-from-bracket text-red-500"></i>
                <span class="item-description">Logout</span>
            </a>
        </div>
    </nav>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">


<script>
    document.getElementById('open_btn').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('open-sidebar');
});
</script>