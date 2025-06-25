@extends('layouts.user_type.auth')

@section('content')
@include('message.alert')
<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl mt-4"
        style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <div class="card card-body  shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
            <div class="col-auto">
                <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://www.gravatar.com/avatar/' . md5(Auth::user()->email) . '?d=identicon' }}"
                    alt="profile_image" class="w-100 border-radius-lg shadow-sm"
                    style="width: 75px; height: 75px; object-fit: cover; border-radius: 50%;">
            </div>
        </div>
        <div class="col-auto my-auto">
            <div class="h-100">
                <!-- Nome do usuário -->
                <h5 class="mb-1">
                    {{ Auth::user()->name }}
                </h5>
                <p class="mb-0 font-weight-bold text-sm">
                    {{ Auth::user()->about_me }}
                    <!-- Exibe o texto "Sobre mim" do usuário -->
                </p>
                <div class="avatar avatar-xl position-relative">
                    <!-- Foto de perfil do usuário -->

                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" id="app-tab" data-bs-toggle="pill" href="#app"
                                role="tab" aria-selected="true">
                                <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42">
                                    <!-- Ícone do app -->
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF"
                                            fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(603.000000, 0.000000)">
                                                    <path class="color-background"
                                                        d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                    </path>
                                                    <path class="color-background"
                                                        d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"
                                                        opacity="0.7"></path>
                                                    <path class="color-background"
                                                        d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"
                                                        opacity="0.7"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <span class="ms-1">Informações</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="messages-tab" data-bs-toggle="pill" href="#messages"
                                role="tab" aria-selected="false">
                                <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44">
                                    <!-- Ícone de mensagens -->
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF"
                                            fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(154.000000, 300.000000)">
                                                    <path class="color-background"
                                                        d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"
                                                        opacity="0.603585379"></path>
                                                    <path class="color-background"
                                                        d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <span class="ms-1">Messages</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="settings-tab" data-bs-toggle="pill" href="#settings"
                                role="tab" aria-selected="false">
                                <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40">
                                    <!-- Ícone de configurações -->
                                    <title>settings</title>
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF"
                                            fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(304.000000, 151.000000)">
                                                    <polygon class="color-background" opacity="0.596981957"
                                                        points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                    </polygon>
                                                    <path class="color-background"
                                                        d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z"
                                                        opacity="0.596981957"></path>
                                                    <path class="color-background"
                                                        d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <span class="ms-1">Configurações</span>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <!-- Conteúdo das abas -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12 col-xl-4">
                        <div class="card h-100">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">Configurações da Plataforma</h6>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Conta</h6>
                                <ul class="list-group">
                                    <li class="list-group-item border-0 px-0">
                                        <div class="form-check form-switch ps-0">
                                            <input class="form-check-input ms-auto" type="checkbox"
                                                id="flexSwitchCheckDefault" checked>
                                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                for="flexSwitchCheckDefault">Me envie um e-mail quando alguém me
                                                seguir</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <div class="form-check form-switch ps-0">
                                            <input class="form-check-input ms-auto" type="checkbox"
                                                id="flexSwitchCheckDefault1">
                                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                for="flexSwitchCheckDefault1">Me envie um e-mail quando alguém responder
                                                ao meu post</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <div class="form-check form-switch ps-0">
                                            <input class="form-check-input ms-auto" type="checkbox"
                                                id="flexSwitchCheckDefault2" checked>
                                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                for="flexSwitchCheckDefault2">Me envie um e-mail quando alguém me
                                                mencionar</label>
                                        </div>
                                    </li>
                                </ul>
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder mt-4">Aplicativo</h6>
                                <ul class="list-group">
                                    <li class="list-group-item border-0 px-0">
                                        <div class="form-check form-switch ps-0">
                                            <input class="form-check-input ms-auto" type="checkbox"
                                                id="flexSwitchCheckDefault3">
                                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                for="flexSwitchCheckDefault3">Novos lançamentos e projetos</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <div class="form-check form-switch ps-0">
                                            <input class="form-check-input ms-auto" type="checkbox"
                                                id="flexSwitchCheckDefault4" checked>
                                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                for="flexSwitchCheckDefault4">Atualizações mensais de produtos</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 px-0 pb-0">
                                        <div class="form-check form-switch ps-0">
                                            <input class="form-check-input ms-auto" type="checkbox"
                                                id="flexSwitchCheckDefault5">
                                            <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                for="flexSwitchCheckDefault5">Inscrever-se na newsletter</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="card h-100">
                            <div class="card-header pb-0 p-3">
                                <div class="row">
                                    <div class="col-md-8 d-flex align-items-center">
                                        <h6 class="mb-0">Informações do Perfil</h6>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a class="nav-link mb-0 px-0 py-1" href="javascript:;">
                                            <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Editar Perfil"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <p class="text-sm">
                                    Olá, eu sou {{ Auth::user()->name }}. Decisões: Se você não pode decidir, a resposta
                                    é não. Se dois caminhos igualmente difíceis, escolha o mais doloroso a curto prazo
                                    (evitar dor cria uma ilusão de igualdade).
                                </p>
                                <hr class="horizontal gray-light my-4">
                                <ul class="list-group">
                                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong
                                            class="text-dark">Nome Completo:</strong> &nbsp; {{ Auth::user()->name }}
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong
                                            class="text-dark">Celular:</strong> &nbsp;
                                        {{ Auth::user()->phone ?? 'Não informado' }}</li>
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong
                                            class="text-dark">E-mail:</strong> &nbsp; {{ Auth::user()->email }}</li>
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong
                                            class="text-dark">Localização:</strong> &nbsp;
                                        {{ Auth::user()->location ?? 'Não informado' }}</li>
                                    <li class="list-group-item border-0 ps-0 pb-0">
                                        <strong class="text-dark text-sm">Social:</strong> &nbsp;
                                        <a class="btn btn-facebook btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                                            <i class="fab fa-facebook fa-lg"></i>
                                        </a>
                                        <a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                                            <i class="fab fa-twitter fa-lg"></i>
                                        </a>
                                        <a class="btn btn-instagram btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                                            <i class="fab fa-instagram fa-lg"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="card h-100">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">Conversations</h6>
                            </div>
                            <div class="card-body p-3">
                                <ul class="list-group">
                                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                        <div class="avatar me-3">
                                            <img src="../assets/img/kal-visuals-square.jpg" alt="kal"
                                                class="border-radius-lg shadow">
                                        </div>
                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Sophie B.</h6>
                                            <p class="mb-0 text-xs">Hi! I need more information..</p>
                                        </div>
                                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                                    </li>
                                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                        <div class="avatar me-3">
                                            <img src="../assets/img/marie.jpg" alt="kal"
                                                class="border-radius-lg shadow">
                                        </div>
                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Anne Marie</h6>
                                            <p class="mb-0 text-xs">Awesome work, can you..</p>
                                        </div>
                                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                                    </li>
                                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                        <div class="avatar me-3">
                                            <img src="../assets/img/ivana-square.jpg" alt="kal"
                                                class="border-radius-lg shadow">
                                        </div>
                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Ivanna</h6>
                                            <p class="mb-0 text-xs">About files I can..</p>
                                        </div>
                                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                                    </li>
                                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                        <div class="avatar me-3">
                                            <img src="../assets/img/team-4.jpg" alt="kal"
                                                class="border-radius-lg shadow">
                                        </div>
                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Peterson</h6>
                                            <p class="mb-0 text-xs">Have a great afternoon..</p>
                                        </div>
                                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                                    </li>
                                    <li class="list-group-item border-0 d-flex align-items-center px-0">
                                        <div class="avatar me-3">
                                            <img src="../assets/img/team-3.jpg" alt="kal"
                                                class="border-radius-lg shadow">
                                        </div>
                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Nick Daniel</h6>
                                            <p class="mb-0 text-xs">Hi! I need more information..</p>
                                        </div>
                                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="card mb-4">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-1">Projects</h6>
                                <p class="text-sm">Architects design houses</p>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                        <div class="card card-blog card-plain">
                                            <div class="position-relative">
                                                <a class="d-block shadow-xl border-radius-xl">
                                                    <img src="../assets/img/home-decor-1.jpg" alt="img-blur-shadow"
                                                        class="img-fluid shadow border-radius-xl">
                                                </a>
                                            </div>
                                            <div class="card-body px-1 pb-0">
                                                <p class="text-gradient text-dark mb-2 text-sm">Project #2</p>
                                                <a href="javascript:;">
                                                    <h5>
                                                        Modern
                                                    </h5>
                                                </a>
                                                <p class="mb-4 text-sm">
                                                    As Uber works through a huge amount of internal management turmoil.
                                                </p>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm mb-0">View
                                                        Project</button>
                                                    <div class="avatar-group mt-2">
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Elena Morison">
                                                            <img alt="Image placeholder" src="../assets/img/team-1.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Ryan Milly">
                                                            <img alt="Image placeholder" src="../assets/img/team-2.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Nick Daniel">
                                                            <img alt="Image placeholder" src="../assets/img/team-3.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Peterson">
                                                            <img alt="Image placeholder" src="../assets/img/team-4.jpg">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                        <div class="card card-blog card-plain">
                                            <div class="position-relative">
                                                <a class="d-block shadow-xl border-radius-xl">
                                                    <img src="../assets/img/home-decor-2.jpg" alt="img-blur-shadow"
                                                        class="img-fluid shadow border-radius-lg">
                                                </a>
                                            </div>
                                            <div class="card-body px-1 pb-0">
                                                <p class="text-gradient text-dark mb-2 text-sm">Project #1</p>
                                                <a href="javascript:;">
                                                    <h5>
                                                        Scandinavian
                                                    </h5>
                                                </a>
                                                <p class="mb-4 text-sm">
                                                    Music is something that every person has his or her own specific
                                                    opinion about.
                                                </p>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm mb-0">View
                                                        Project</button>
                                                    <div class="avatar-group mt-2">
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Nick Daniel">
                                                            <img alt="Image placeholder" src="../assets/img/team-3.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Peterson">
                                                            <img alt="Image placeholder" src="../assets/img/team-4.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Elena Morison">
                                                            <img alt="Image placeholder" src="../assets/img/team-1.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Ryan Milly">
                                                            <img alt="Image placeholder" src="../assets/img/team-2.jpg">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                        <div class="card card-blog card-plain">
                                            <div class="position-relative">
                                                <a class="d-block shadow-xl border-radius-xl">
                                                    <img src="../assets/img/home-decor-3.jpg" alt="img-blur-shadow"
                                                        class="img-fluid shadow border-radius-xl">
                                                </a>
                                            </div>
                                            <div class="card-body px-1 pb-0">
                                                <p class="text-gradient text-dark mb-2 text-sm">Project #3</p>
                                                <a href="javascript:;">
                                                    <h5>
                                                        Minimalist
                                                    </h5>
                                                </a>
                                                <p class="mb-4 text-sm">
                                                    Different people have different taste, and various types of music.
                                                </p>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm mb-0">View
                                                        Project</button>
                                                    <div class="avatar-group mt-2">
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Peterson">
                                                            <img alt="Image placeholder" src="../assets/img/team-4.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Nick Daniel">
                                                            <img alt="Image placeholder" src="../assets/img/team-3.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Ryan Milly">
                                                            <img alt="Image placeholder" src="../assets/img/team-2.jpg">
                                                        </a>
                                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Elena Morison">
                                                            <img alt="Image placeholder" src="../assets/img/team-1.jpg">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                        <div class="card h-100 card-plain border">
                                            <div
                                                class="card-body d-flex flex-column justify-content-center text-center">
                                                <a href="javascript:;">
                                                    <i class="fa fa-plus text-secondary mb-3"></i>
                                                    <h5 class=" text-secondary"> New project </h5>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card h-100">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">Mensagens</h6>
                            </div>
                            <div class="card-body p-3">
                                <p class="text-sm">Nenhuma mensagem disponível.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <div class="row">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header pb-0 px-3">
                            <h6 class="mb-0">{{ __('Editar Perfil') }}</h6>
                        </div>
                        <div class="card-body pt-4 p-3">
                            <form action="/user-profile" method="POST" role="form text-left"
                                enctype="multipart/form-data">
                                @csrf
                                @if($errors->any())
                                <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                                    <span class="alert-text text-white">{{$errors->first()}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @endif
                                @if(session('success'))
                                <div class="m-3 alert alert-success alert-dismissible fade show" role="alert">
                                    <span class="alert-text text-white">{{ session('success') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user-name"
                                                class="form-control-label">{{ __('Nome Completo') }}</label>
                                            <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                                <input class="form-control" value="{{ auth()->user()->name }}"
                                                    type="text" placeholder="Nome" id="user-name" name="name">
                                                @error('name')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user-email"
                                                class="form-control-label">{{ __('E-mail') }}</label>
                                            <div class="@error('email')border border-danger rounded-3 @enderror">
                                                <input class="form-control" value="{{ auth()->user()->email }}"
                                                    type="email" placeholder="@example.com" id="user-email"
                                                    name="email">
                                                @error('email')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user-phone"
                                                class="form-control-label">{{ __('Celular') }}</label>
                                            <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                                <input class="form-control" type="tel" placeholder="40770888444"
                                                    id="number" name="phone" value="{{ auth()->user()->phone }}">
                                                @error('phone')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user-location"
                                                class="form-control-label">{{ __('Localização') }}</label>
                                            <div
                                                class="@error('user.location') border border-danger rounded-3 @enderror">
                                                <input class="form-control" type="text" placeholder="Localização"
                                                    id="name" name="location" value="{{ auth()->user()->location }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="about">{{ __('Sobre Mim') }}</label>
                                    <div class="@error('user.about')border border-danger rounded-3 @enderror">
                                        <textarea class="form-control" id="about" rows="3"
                                            placeholder="Fale algo sobre você"
                                            name="about_me">{{ auth()->user()->about_me }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profile-picture">{{ __('Foto de Perfil') }}</label>
                                            <input class="form-control" type="file" id="profile-picture"
                                                name="profile_picture" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="profile-picture-preview">{{ __('Pré-visualizar Foto Cortada') }}</label>
                                            <div id="image-preview-container">
                                                <img id="imagePreview" src="" class="img-fluid"
                                                    style="max-width: 100%; display:none; border-radius: 10px;">
                                            </div>
                                            <button type="button" id="cropBtn" class="btn btn-success mt-2"
                                                style="display:none;">Cortar Imagem</button>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="profile_picture_crop" id="profile-picture-crop">

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4"
                                        id="saveBtn">{{ __('Salvar Alterações') }}</button>
                                </div>
                            </form>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js">
                            </script>
                            <script>
                            let cropper;
                            const profilePicInput = document.getElementById('profile-picture');
                            const imagePreview = document.getElementById('imagePreview');
                            const cropBtn = document.getElementById('cropBtn');
                            const saveBtn = document.getElementById('saveBtn');
                            const profilePicCropInput = document.getElementById('profile-picture-crop');

                            profilePicInput.addEventListener('change', function(e) {
                                const file = e.target.files[0];
                                const reader = new FileReader();

                                reader.onload = function(event) {
                                    imagePreview.src = event.target.result;
                                    imagePreview.style.display = 'block';
                                    cropBtn.style.display = 'inline-block'; // Exibe o botão de cortar

                                    if (cropper) {
                                        cropper
                                            .destroy(); // Destrua qualquer instância anterior do cropper
                                    }

                                    cropper = new Cropper(imagePreview, {
                                        aspectRatio: 1, // Mantém proporção quadrada
                                        viewMode: 2,
                                        scalable: true,
                                        responsive: true,
                                        zoomable: true,
                                        minCropBoxWidth: 100, // Tamanho mínimo do box de corte
                                        minCropBoxHeight: 100, // Tamanho mínimo do box de corte
                                        cropBoxResizable: true,
                                        crop: function(event) {
                                            // Aqui cortamos a imagem e preparamos ela para o envio
                                            const canvas = cropper.getCroppedCanvas({
                                                width: 150, // Define o tamanho do corte
                                                height: 150, // Tamanho do corte, ajustável
                                                fillColor: '#fff', // Cor de fundo
                                                imageSmoothingEnabled: true,
                                                imageSmoothingQuality: 'high'
                                            });

                                            // Exibindo a imagem cortada no formato redondo
                                            const roundedCanvas = document.createElement(
                                                'canvas');
                                            const ctx = roundedCanvas.getContext('2d');
                                            roundedCanvas.width = 150;
                                            roundedCanvas.height = 150;

                                            ctx.beginPath();
                                            ctx.arc(75, 75, 75, 0, 2 * Math
                                                .PI); // Cria o círculo
                                            ctx.clip(); // Recorta a imagem

                                            ctx.drawImage(canvas, 0, 0);

                                            // Armazena a imagem cortada em formato base64
                                            profilePicCropInput.value = roundedCanvas
                                                .toDataURL(); // Salva a imagem cortada
                                        }
                                    });
                                };

                                if (file) {
                                    reader.readAsDataURL(file);
                                }
                            });

                            // O botão "Cortar" é clicado para cortar a imagem
                            cropBtn.addEventListener('click', function() {
                                cropper.getCroppedCanvas({
                                    width: 150,
                                    height: 150,
                                }).toBlob(function(blob) {});
                            });

                            // Ao clicar no botão de salvar, o formulário será enviado
                            saveBtn.addEventListener('click', function() {
                                // Aqui você pode adicionar uma lógica para verificar se a imagem foi cortada
                            });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth.footer')

    @endsection