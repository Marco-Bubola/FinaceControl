@php
    $user = Auth::user();
    $userName = $user ? ucwords($user->name) : 'Usuário';
    $userEmail = $user ? $user->email : '';
    $userPhoto = $user && isset($user->profile_picture) ? asset('storage/' . $user->profile_picture) : asset('assets/img/default-avatar.png');
@endphp
<!-- User Profile & Navbar Icons -->
<li class="nav-item d-xl-none ps-3 d-flex align-items-center">
    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
        <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
        </div>
    </a>
</li>
<li class="nav-item px-3 d-flex align-items-center">
    <a href="javascript:;" class="nav-link text-body p-0" title="Configurações">
        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer fs-5"></i>
    </a>
</li>
<li class="nav-item dropdown pe-2 d-flex align-items-center">
    <a href="#" class="nav-link text-body p-0 position-relative" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Notificações">
        <i class="fa fa-bell cursor-pointer fs-5"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger animate__animated animate__bounceIn" style="font-size: 0.7rem;">3</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end px-3 py-3 me-sm-n4 shadow-lg border-0 rounded-4" aria-labelledby="dropdownMenuButton" style="min-width: 340px; background: #fff; border-radius: 1rem; transition: box-shadow 0.3s;">
        <li class="mb-2">
            <a class="dropdown-item d-flex align-items-center gap-3 py-2" href="#" style="border-radius: 0.75rem; transition: background 0.2s;">
                <img src="../assets/img/team-2.jpg" class="avatar avatar-sm rounded-circle me-2 shadow-sm border border-2 border-primary" alt="User">
                <div class="flex-grow-1">
                    <h6 class="text-sm fw-semibold mb-1">Nova mensagem de Laur</h6>
                    <p class="text-xs text-secondary mb-0"><i class="fa fa-clock me-1"></i>13 minutos atrás</p>
                </div>
            </a>
        </li>
        <li class="mb-2">
            <a class="dropdown-item d-flex align-items-center gap-3 py-2" href="#" style="border-radius: 0.75rem; transition: background 0.2s;">
                <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark rounded-circle me-2 shadow-sm border border-2 border-success" alt="Spotify">
                <div class="flex-grow-1">
                    <h6 class="text-sm fw-semibold mb-1">Novo álbum de Travis Scott</h6>
                    <p class="text-xs text-secondary mb-0"><i class="fa fa-clock me-1"></i>1 dia</p>
                </div>
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-3 py-2" href="#" style="border-radius: 0.75rem; transition: background 0.2s;">
                <span class="avatar avatar-sm bg-gradient-secondary rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm border border-2 border-warning">
                    <i class="fa fa-credit-card text-white"></i>
                </span>
                <div class="flex-grow-1">
                    <h6 class="text-sm fw-semibold mb-1">Pagamento realizado com sucesso</h6>
                    <p class="text-xs text-secondary mb-0"><i class="fa fa-clock me-1"></i>2 dias</p>
                </div>
            </a>
        </li>
    </ul>
</li>
<!-- User Dropdown -->
<!-- User Dropdown TailwindCSS -->
<li class="relative group list-none">
<button type="button" class="flex items-center justify-center w-12 h-12 rounded-full bg-white border border-gray-200 shadow hover:shadow-lg transition focus:outline-none focus:ring-2 focus:ring-primary-500 relative" id="userProfileDropdownTW" aria-haspopup="true" aria-expanded="false">
<img src="{{ $userPhoto }}" alt="Avatar de {{ $userName }}" class="w-full h-full rounded-full object-cover" />
<span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
</button>
<div class="hidden group-hover:block group-focus-within:block absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 animate-fade-in-up transition-all" role="menu" aria-labelledby="userProfileDropdownTW">
<div class="flex flex-col items-center py-5 px-6">
<img src="{{ $userPhoto }}" alt="Avatar de {{ $userName }}" class="w-20 h-20 rounded-full object-cover border-4 border-primary-500 shadow mb-2">
<div class="font-semibold text-lg text-gray-800">{{ $userName }}</div>
<div class="text-gray-400 text-sm mb-2">{{ $userEmail }}</div>
</div>
<div class="border-t border-gray-100"></div>
<a href="{{ url('/profile') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition rounded-xl" role="menuitem">
<i class="fa fa-user-cog text-primary-500"></i>
<span>Configurações</span>
</a>
<a href="{{ url('/logout') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition rounded-xl" role="menuitem">
<i class="fa fa-sign-out-alt text-red-500"></i>
<span>Sair</span>
</a>
</div>
</li>
<!-- Fim do User Profile -->
<!--
Dicas:
- Para animações, use animate.css (adicione <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" /> no layout principal se ainda não tiver).
- Para mais ícones, use Font Awesome 5+ (<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />).
- Se quiser ainda mais modernidade, pode integrar TailwindCSS, mas este exemplo é 100% Bootstrap + FontAwesome.
-->