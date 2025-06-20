<aside class="sidenav navbar navbar-vertical overflow-hidden navbar-expand-xs fixed-start" id="sidenav-main">
    <div class="sidenav-header d-flex align-items-center justify-content-between flex-nowrap">
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
            <img src="../assets/img/logo-ct.png" class="navbar-brand-img" alt="..." />
            <span class="ms-3 font-weight-bold sidebar-text"> Flow Manege</span>
        </a>
        <!-- Botão para alternar sidebar SEMPRE visível -->
        <button id="toggleSidebar" class="btn btn-link toggle-sidebar-btn" style="font-size: 1.5rem;">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="sidebar-body" style=" display: flex; flex-direction: column;">
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }}" href="{{ url('dashboard') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>shop </title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(0.000000, 148.000000)">
                                                <path class="color-background opacity-6" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                                                <path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Painel de Controle</span>
                    </a>
                </li>
                <li class="nav-item mt-2 sidebar-text">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Perfil de Usuário</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('profile') ? 'active' : '') }}" href="{{ url('profile') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>customer-support</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(1.000000, 0.000000)">
                                                <path class="color-background opacity-6" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z"></path>
                                                <path class="color-background" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z"></path>
                                                <path class="color-background" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Perfil</span>
                    </a>
                </li>
                @if (auth()->user() && auth()->user()->role_id == 1)
                <li class="nav-item pb-2">
                    <a class="nav-link {{ (Request::is('user-management') ? 'active' : '') }}" href="{{ url('user-management') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>customer-support</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(1.000000, 0.000000)">
                                                <path class="color-background opacity-6" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z"></path>
                                                <path class="color-background" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z"></path>
                                                <path class="color-background" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Gerenciamento</span>
                    </a>
                </li>
                @endif
                <li class="nav-item mt-2 sidebar-text">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Páginas de Exemplo</h6>
                </li>
                                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('banks') ? 'active' : '') }}" href="{{ url('banks') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>credit-card</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(453.000000, 454.000000)">
                                                <path class="color-background opacity-6" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"></path>
                                                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Bancos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('cashbook') ? 'active' : '') }}" href="{{ url('cashbook') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>credit-card</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(453.000000, 454.000000)">
                                                <path class="color-background opacity-6" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"></path>
                                                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Livro Caixa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('cofrinho') ? 'active' : '') }}" href="{{ url('cofrinho') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-piggy-bank text-warning" style="font-size:1.5rem;"></i>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Cofrinhos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('products') ? 'active' : '') }}" href="{{ url('products') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box2" viewBox="0 0 16 16">
                                <path d="M2.95.4a1 1 0 0 1 .8-.4h8.5a1 1 0 0 1 .8.4l2.85 3.8a.5.5 0 0 1 .1.3V15a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4.5a.5.5 0 0 1 .1-.3zM7.5 1H3.75L1.5 4h6zm1 0v3h6l-2.25-3zM15 5H1v10h14z" />
                            </svg>

                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Produtos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('clients') ? 'active' : '') }}" href="{{ url('clients') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
                                <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.4 5.4 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2z" />
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Clientes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('sales') ? 'active' : '') }}" href="{{ url('sales') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                                <path d="M4 0a1 1 0 0 0-1 1v1h12V1a1 1 0 0 0-1-1H4zM0 4a1 1 0 0 1 1-1h1l1 6h8l1-6h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 7v1h2V6H3v5zm3 0v1h2V7H6v4zm3 0v1h2V8H9v3zm3 0v1h2V9h-2v2z" />
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Vendas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('categories') ? 'active' : '') }}" href="{{ url('categories') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
                                <path d="M6 0a1 1 0 0 1 1 1v3.381l3.585-1.79a1 1 0 1 1 .83 1.79L7 6.536V10a1 1 0 0 1-.707.948L4 11.455v1.434l2.293-.787A1 1 0 0 1 7 12V9.536l4.585 2.295a1 1 0 1 1-.83 1.79L7 8.383V4.617l3.585-1.79A1 1 0 0 1 11 3.383l2.292-.787a1 1 0 0 1 1.268 1.268L10.382 5.618 6 0z" />
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Categorias</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('dashboard/cashbook') ? 'active' : '') }}" href="{{ url('dashboard/cashbook') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                <path d="M12 4H2a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v2zm0 1V2H3v3h9zm2 1a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h12z"/>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Dashboard Cashbook</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('dashboard/sales') ? 'active' : '') }}" href="{{ url('dashboard/sales') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">
                                <path d="M0 0h1v15h15v1H0V0zm10 10h2V5h-2v5zm-3 0h2V1H7v9zm-3 0h2V7H4v3z"/>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Dashboard Vendas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Request::is('dashboard/products') ? 'active' : '') }}" href="{{ url('dashboard/products') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                                <path d="M8.186.113a1.5 1.5 0 0 0-1.372 0l-6 3A1.5 1.5 0 0 0 0 4.382V12.5A1.5 1.5 0 0 0 .803 13.82l6 2.25a1.5 1.5 0 0 0 1.394 0l6-2.25A1.5 1.5 0 0 0 16 12.5V4.382a1.5 1.5 0 0 0-.814-1.269l-6-3ZM8 1.058 14.481 4 8 6.942 1.519 4 8 1.058ZM1 5.383l6 2.25v7.384l-6-2.25V5.383Zm7 9.634v-7.384l6-2.25v7.384l-6 2.25Z"/>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1 sidebar-text">Dashboard Produtos</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
</aside>

<style>
    html, body {
        overflow: hidden;
    }
    .sidenav {
        position: fixed;
        top: 0;
        left: 0;
        width: 350px;
        display: flex;
        flex-direction: column;
        z-index: 1040;
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
        border-radius: 1rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        transition: border-radius 0.2s, width 0.2s, box-shadow 0.2s;
        
    }
    .sidebar-body {
        flex: 1 1 auto;
       overflow: hidden;
        display: block;
        padding: 0;
        margin: 0;
    }
    .collapse.navbar-collapse {
        padding: 0;
        margin: 0;
        min-height: 0;
        display: block;
    }
    .navbar-nav {
        padding: 0;
        margin: 0;
        display: block;
    }
    .navbar-nav > li:last-child {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    .sidebar-collapsed {
        width: 80px !important;
        min-width: 80px !important;
        max-width: 80px !important;
        border-radius: 0.5rem !important;
        transition: border-radius 0.2s, width 0.2s;
    }
    .sidebar-collapsed .sidebar-text,
    .sidebar-collapsed .docs-info,
    .sidebar-collapsed h6.sidebar-text {
        display: none !important;
    }
    .sidebar-collapsed .navbar-brand-img {
        margin-right: 0 !important;
        display: block !important;
        max-width: 40px !important;
        max-height: 40px !important;
       
    }
    .navbar-brand-img {
        max-width: 40px;
        max-height: 40px;
        width: auto;
        height: auto;
        display: block;
    }
    .sidebar-collapsed .navbar-brand {
        justify-content: center !important;
    }
    .sidebar-collapsed .nav-link {
        justify-content: center;
    }
    .sidebar-collapsed .icon {
        margin-right: 0 !important;
    }
    /* Ícones SVG maiores e centralizados */
    .icon svg,
    .icon-shape svg,
    .icon .bi,
    .icon-shape .bi {
        width: 2.2rem !important;
        height: 2.2rem !important;
        display: block;
        margin: 0 auto;
    }
    .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 48px;
        min-height: 48px;
        background: #f1f5fa;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(31,38,135,0.04);
        transition: background 0.2s;
    }
    .nav-link {
        border-radius: 0.75rem;
        transition: background 0.2s, color 0.2s;
       
        display: flex;
        align-items: center;
    }
    .nav-link.active, .nav-link:hover {
        background: #f3f4f6 !important;
        color: #222 !important;
        box-shadow: none;
    }
    .nav-link.active .icon,
    .nav-link:hover .icon {
        background: #fff;
    }
    .sidebar-collapsed .toggle-sidebar-btn {
        margin-left: 0 !important;
        margin-right: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .sidebar-collapsed .sidenav-header {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
       height: 130px;
    }
    .sidenav-header {
        width: 100%;
        
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .sidebar-collapsed #toggleSidebar {
        margin-left: 0 !important;
    }
    body.with-sidebar-expanded {
        margin-left: 250px;
        transition: margin-left 0.2s;
    }
    body.with-sidebar-collapsed {
        margin-left: 80px;
        transition: margin-left 0.2s;
    }
    @media (max-width: 991.98px) {
        body.with-sidebar-expanded,
        body.with-sidebar-collapsed {
            margin-left: 0 !important;
        }
        .sidenav {
            position: fixed;
            left: 0;
            z-index: 1040;
        }
    }
    .sidebar-collapsed .sidebar-text {
        display: none !important;
        margin: 0 !important; /* Adicione esta linha para remover o espaço */
    }
</style>

<!-- Script para alternar sidebar -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const aside = document.getElementById('sidenav-main');
        const btn = document.getElementById('toggleSidebar');
        document.body.classList.add('with-sidebar-expanded');
        btn.addEventListener('click', function () {
            aside.classList.toggle('sidebar-collapsed');
            if (aside.classList.contains('sidebar-collapsed')) {
                document.body.classList.remove('with-sidebar-expanded');
                document.body.classList.add('with-sidebar-collapsed');
            } else {
                document.body.classList.remove('with-sidebar-collapsed');
                document.body.classList.add('with-sidebar-expanded');
            }
        });
    });
</script>
