<nav id="sidebar">
        <div id="sidebar_content">
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

  
 <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    display: flex;
    min-height: 100vh;
    background-color: #e3e9f7;
}

main {
    padding: 20px;
    position: fixed;
    z-index: 1;
    padding-left: calc(82px + 20px);
}

#sidebar {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background-color: #ffffff;
    height: 100vh;
    border-radius: 0px 18px 18px 0px;
    position: relative;
    transition: all .5s;
    min-width: 82px;
    z-index: 2;
}

#sidebar_content {
    padding: 12px;
}

#user {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
}

#user_avatar {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 20px;
    background: #f3f3f3;
    border: 1px solid #e3e9f7;
}

#user_infos {
    display: flex;
    flex-direction: column;
}

#user_infos span:last-child {
    color: #6b6b6b;
    font-size: 12px;
}

#user_infos .user-name {
    color: #222 !important;
    font-weight: 600;
}

#side_items {
    display: flex;
    flex-direction: column;
    gap: 8px;
    list-style: none;
}

.side-item {
    border-radius: 8px;
    padding: 14px;
    cursor: pointer;
}

.side-item.active {
    background-color: #4f46e5;
}

.side-item:hover:not(.active),
#logout_btn:hover {
    background-color: #e3e9f7;
}

.side-item a {
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0a0a0a;
}

.side-item.active a {
    color: #e3e9f7;
}

.side-item a i {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem; /* Ícones maiores, mas não exagerados */
    transition: color 0.2s;
}

#logout_btn i {
    font-size: 1.5rem;
}

.side-item {
    border-radius: 12px;
    padding: 18px 18px 18px 14px;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.03);
}

.side-item.active {
    background-color: #4f46e5;
    box-shadow: 0 2px 8px 0 rgba(79,70,229,0.10);
}

.side-item:hover:not(.active),
#logout_btn:hover {
    background-color: #f1f5ff;
    color: #4f46e5;
    box-shadow: 0 2px 8px 0 rgba(79,70,229,0.08);
}

.side-item a {
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    color: #222;
    gap: 18px;
    font-weight: 500;
    font-size: 1.08rem;
}

.side-item.active a {
    color: #fff;
}

#logout_btn {
    border: none;
    padding: 16px 18px;
    font-size: 1.08rem;
    display: flex;
    gap: 18px;
    align-items: center;
    border-radius: 12px;
    text-align: start;
    cursor: pointer;
    background-color: transparent;
    color: #e53e3e;
    font-weight: 500;
    margin-top: 10px;
    transition: background 0.2s, color 0.2s;
}

#logout_btn:hover {
    background-color: #ffeaea;
    color: #c53030;
}

.item-description {
    width: 0px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    font-size: 16px;
    transition: width .6s, color 0.2s;
    height: 0px;
    font-weight: 500;
    letter-spacing: 0.01em;
}

#sidebar.open-sidebar .item-description {
    width: 170px;
    height: auto;
}

#sidebar.open-sidebar .side-item a {
    justify-content: flex-start;
    gap: 18px;
}

#user_avatar {
    width: 60px;
    height: 60px;
    border-radius: 24px;
    border: 2px solid #4f46e5;
}

#user_infos .user-name {
    color: #222 !important;
    font-weight: 700;
    font-size: 1.1rem;
}

#user_infos span:last-child {
    color: #6b6b6b;
    font-size: 13px;
}

#sidebar {
    box-shadow: 0 4px 24px 0 rgba(79,70,229,0.08);
}

#logout {
    border-top: 1px solid #e3e9f7;
    padding: 12px;
}

#logout_btn {
    border: none;
    padding: 12px;
    font-size: 14px;
    display: flex;
    gap: 20px;
    align-items: center;
    border-radius: 8px;
    text-align: start;
    cursor: pointer;
    background-color: transparent;
}

#open_btn {
    position: absolute;
    top: 30px;
    right: -10px;
    background-color: #4f46e5;
    color: #e3e9f7;
    border-radius: 100%;
    width: 20px;
    height: 20px;
    border: none;
    cursor: pointer;
}

#open_btn_icon {
    transition: transform .3s ease;
}

.open-sidebar #open_btn_icon {
    transform: rotate(180deg);
}

.item-description {
    width: 0px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    font-size: 14px;
    transition: width .6s;
    height: 0px;
}

#sidebar.open-sidebar {
    min-width: 15%;
}

#sidebar.open-sidebar .item-description {
    width: 150px;
    height: auto;
}

#sidebar.open-sidebar .side-item a {
    justify-content: flex-start;
    gap: 14px;
}
</style>   

<script>
    document.getElementById('open_btn').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('open-sidebar');
});
</script>