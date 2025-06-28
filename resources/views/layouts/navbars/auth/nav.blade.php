<nav id="main-navbar"
    class="sticky top-0 left-0 w-full h-14 z-40 flex items-center justify-between navbar-light shadow-md backdrop-blur">

    <!-- Esquerda: Sidenav Toggler -->
    <div class="flex items-center gap-3 pl-4 pr-2">
        <img src="../assets/img/logo-ct.png" class="navbar-brand-img" alt="Logo FlowManege" />
        <span class="navbar-app-name font-bold text-lg tracking-wide">FlowManege</span>
    </div>
    <!-- Direita: Notificações, Configurações, Dark Mode -->
    <div class="navbar-actions flex items-center">
        <!-- Botão de Notificações -->
        <div class="relative">
            <button id="notification-btn" type="button"
                class="w-10 h-10 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none"
                aria-label="Notificações">
                <i class="fa fa-bell text-gray-700 dark:text-gray-200 text-xl"></i>
            </button>
            <!-- Dropdown -->
            <div id="notification-dropdown"
                class="hidden absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 border border-gray-100 dark:border-gray-700">
                <div
                    class="p-4 border-b border-gray-100 dark:border-gray-700 font-semibold text-gray-700 dark:text-gray-200">
                    Notificações</div>
                <ul class="max-h-60 overflow-y-auto">
                    <li
                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                        <img src="../assets/img/team-2.jpg" class="w-8 h-8 rounded-full" alt="Avatar">
                        <div>
                            <div class="text-sm text-gray-800 dark:text-gray-100">Novo <span
                                    class="font-bold">mensagem</span> de Laur</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1"><i
                                    class="fa fa-clock"></i> 13 minutos atrás</div>
                        </div>
                    </li>
                    <li
                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                        <img src="../assets/img/small-logos/logo-spotify.svg"
                            class="w-8 h-8 rounded bg-gray-200 dark:bg-gray-700" alt="Logo">
                        <div>
                            <div class="text-sm text-gray-800 dark:text-gray-100">Novo <span
                                    class="font-bold">álbum</span> de Travis Scott</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1"><i
                                    class="fa fa-clock"></i> 1 dia</div>
                        </div>
                    </li>
                    <li
                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                        <span
                            class="w-8 h-8 flex items-center justify-center rounded bg-gradient-to-tr from-gray-400 to-gray-700 text-white"><i
                                class="fa fa-credit-card"></i></span>
                        <div>
                            <div class="text-sm text-gray-800 dark:text-gray-100">Pagamento realizado</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1"><i
                                    class="fa fa-clock"></i> 2 dias</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <button id="theme-toggle" type="button"
            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <svg id="theme-toggle-dark-icon" class="hidden" width="24px" height="24px"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                id="moonstars" x="0px" y="0px" viewBox="0 0 208.1712 194.1436"
                enable-background="new 0 0 208.1712 194.1436" xml:space="preserve" width="512" height="512">
                <g>
                    <path fill="#D0E8FF"
                        d="M126.3451,50.1103c5.8535,26.7988-2.7402,55.0547-23.1172,74.0566   c-20.3789,19.0039-49.1719,25.5938-75.4883,17.8965c3.0117,6.0898,6.9043,11.7051,11.625,16.7676   c12.8145,13.7422,30.2129,21.6719,48.9922,22.3281c18.7559,0.6309,36.6895-6.043,50.4316-18.8574   c13.7441-12.8145,21.6738-30.2148,22.3281-48.9941c0.6562-18.7773-6.0391-36.6875-18.8555-50.4297   C137.5423,57.8154,132.2103,53.5419,126.3451,50.1103z" />
                    <path fill="#1C71DA"
                        d="M121.6166,38.7958c-1.4492-0.6191-3.1367-0.3281-4.2988,0.752c-1.1562,1.0801-1.5684,2.7402-1.0508,4.2363   c9.1152,26.3008,1.8555,55.5566-18.4961,74.5312c-20.3496,18.9785-50.0469,24.1816-75.6406,13.252   c-1.4551-0.6172-3.1406-0.3262-4.2988,0.7539c-1.1562,1.0801-1.5684,2.7402-1.0508,4.2363   c3.584,10.3379,9.2129,19.668,16.7324,27.7305c14.2715,15.3047,33.6504,24.1348,54.5645,24.8652   c0.9375,0.0332,1.8691,0.0488,2.8027,0.0488c19.8867,0,38.7461-7.416,53.3652-21.0488   c15.3047-14.2734,24.1367-33.6504,24.8672-54.5664c0.7305-20.9141-6.7285-40.8613-21-56.166   C140.5951,49.3603,131.681,43.0927,121.6166,38.7958z M138.7884,162.3017c-13.7422,12.8145-31.6758,19.4883-50.4316,18.8574   c-18.7793-0.6562-36.1777-8.5859-48.9922-22.3281c-4.7207-5.0625-8.6133-10.6777-11.625-16.7676   c26.3164,7.6973,55.1094,1.1074,75.4883-17.8965c20.377-19.002,28.9707-47.2578,23.1172-74.0566   c5.8652,3.4316,11.1973,7.7051,15.916,12.7676c12.8164,13.7422,19.5117,31.6523,18.8555,50.4297   C160.4623,132.0869,152.5326,149.4872,138.7884,162.3017z" />
                    <path fill="#1C71DA"
                        d="M68.2298,42.6962c0.7812,0.7812,1.8047,1.1719,2.8281,1.1719s2.0469-0.3906,2.8281-1.1719l5.7288-5.7285   l5.7283,5.7285c0.7812,0.7812,1.8047,1.1719,2.8281,1.1719s2.0469-0.3906,2.8281-1.1719c1.5625-1.5625,1.5625-4.0938,0-5.6562   l-5.7278-5.7275l5.5774-5.5771c1.5625-1.5625,1.5625-4.0938,0-5.6562s-4.0938-1.5625-5.6562,0l-5.5774,5.5771l-5.5789-5.5791   c-1.5625-1.5625-4.0938-1.5625-5.6562,0s-1.5625,4.0938,0,5.6562l5.5784,5.5781L68.2298,37.04   C66.6673,38.6025,66.6673,41.1337,68.2298,42.6962z" />
                    <path fill="#1C71DA"
                        d="M206.9994,81.04l-5.7285-5.7285l5.5781-5.5781c1.5625-1.5625,1.5625-4.0938,0-5.6562   s-4.0938-1.5625-5.6562,0l-5.5781,5.5781l-5.5781-5.5781c-1.5625-1.5625-4.0938-1.5625-5.6562,0s-1.5625,4.0938,0,5.6562   l5.5781,5.5781l-5.7285,5.7285c-1.5625,1.5625-1.5625,4.0938,0,5.6562c0.7812,0.7812,1.8047,1.1719,2.8281,1.1719   s2.0469-0.3906,2.8281-1.1719l5.7285-5.7285l5.7285,5.7285c0.7812,0.7812,1.8047,1.1719,2.8281,1.1719s2.0469-0.3906,2.8281-1.1719   C208.5619,85.1337,208.5619,82.6025,206.9994,81.04z" />
                    <path fill="#1C71DA"
                        d="M21.8861,114.6962l5.7285-5.7285l5.7285,5.7285c0.7812,0.7812,1.8047,1.1719,2.8281,1.1719   s2.0469-0.3906,2.8281-1.1719c1.5625-1.5625,1.5625-4.0938,0-5.6562l-5.7285-5.7285l5.5781-5.5781   c1.5625-1.5625,1.5625-4.0938,0-5.6562s-4.0938-1.5625-5.6562,0l-5.5781,5.5781l-5.5781-5.5781   c-1.5625-1.5625-4.0938-1.5625-5.6562,0s-1.5625,4.0938,0,5.6562l5.5781,5.5781l-5.7285,5.7285   c-1.5625,1.5625-1.5625,4.0938,0,5.6562c0.7812,0.7812,1.8047,1.1719,2.8281,1.1719S21.1048,115.4775,21.8861,114.6962z" />
                </g>
                <path fill="#FF5D5D"
                    d="M152,22.1436c-1.0239,0-2.0474-0.3906-2.8286-1.1714c-1.562-1.5625-1.562-4.0947,0-5.6572l14.1421-14.1421  c1.5625-1.5615,4.0947-1.5615,5.6572,0c1.562,1.5625,1.562,4.0947,0,5.6572l-14.1421,14.1421  C154.0474,21.753,153.0239,22.1436,152,22.1436z" />
                <path fill="#FF5D5D"
                    d="M166.1421,22.1422c-1.0239,0-2.0474-0.3906-2.8286-1.1714L149.1714,6.8282  c-1.562-1.5625-1.562-4.0952,0-5.6572c1.5635-1.5615,4.0957-1.561,5.6572,0l14.1421,14.1426c1.562,1.5625,1.562,4.0952,0,5.6572  C168.1895,21.7515,167.1655,22.1422,166.1421,22.1422z" />
                <path fill="#00D40B"
                    d="M14,194.1436c-7.7197,0-14-6.2803-14-14s6.2803-14,14-14s14,6.2803,14,14S21.7197,194.1436,14,194.1436z   M14,174.1436c-3.3086,0-6,2.6914-6,6s2.6914,6,6,6s6-2.6914,6-6S17.3086,174.1436,14,174.1436z" />
                <path fill="#FFC504"
                    d="M183.3135,148.7691c-1.0239,0-2.0474-0.3906-2.8286-1.1714l-11.3135-11.3135  c-1.562-1.5625-1.562-4.0947,0-5.6572l11.3135-11.3135c1.5625-1.5615,4.0952-1.5615,5.6567,0l11.314,11.3135  c0.7505,0.7505,1.1719,1.7676,1.1719,2.8286s-0.4214,2.0781-1.1719,2.8286l-11.314,11.3135  C185.3608,148.3785,184.3369,148.7691,183.3135,148.7691z M177.6567,133.4556l5.6567,5.6567l5.6572-5.6567l-5.6572-5.6567  L177.6567,133.4556z" />
            </svg>
            <svg id="theme-toggle-light-icon" class="hidden" width="24px" height="24px" viewBox="0 0 128 128"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                role="img" class="iconify iconify--noto" preserveAspectRatio="xMidYMid meet">
                <path
                    d="M37.41 41.95c-9.71 12.48-9.54 34.65 2.87 45.64c14.09 12.47 33.92 12.34 46.39.87c14.95-13.76 14.09-36.66.87-49.63c-13.29-13.04-37.04-13.72-50.13 3.12z"
                    fill="#fcc11a"></path>
                <path
                    d="M53 37.67c-3.84-1.7-8.04 2.93-9.87 6.09c-1.83 3.17-3.53 9.38.37 10.97c3.9 1.58 6.7-1.1 9.51-5.73c2.79-4.63 4.38-9.38-.01-11.33z"
                    fill="#fee269"></path>
                <path
                    d="M63 20.27c-.93 1.74-.62 3.08 1.23 3.52c1.85.44 13.36 2.31 14.33 2.37c1.41.09 1.93-.97 1.76-2.2c-.18-1.23-2.99-18.46-3.25-20.04S75.14.76 73.55 2.87S63.7 18.96 63 20.27z"
                    fill="#ffa722"></path>
                <path
                    d="M92.8 32.23c-1.81.56-1.76 1.67-.79 3.08c.97 1.41 7.65 11.6 8.26 12.31c.62.7 1.67.88 2.55-.18c.88-1.05 11.86-16.45 12.66-17.41c1.32-1.58.53-3.25-1.49-2.73c-1.54.41-20.05 4.58-21.19 4.93z"
                    fill="#ffa722"></path>
                <path
                    d="M106.6 61.86c-1.3-.74-2.99-.53-3.43 1.14c-.44 1.67-2.37 13.8-2.55 14.86s.62 2.11 1.93 1.85s19.45-2.95 20.66-3.25c2.11-.53 2.81-2.64.62-4.22c-1.42-1.03-16-9.68-17.23-10.38z"
                    fill="#ffa722"></path>
                <path
                    d="M92.09 90.6c1.4-.75 2.64-.18 2.99 1.41c.35 1.58 4.22 17.76 4.84 20.75c.31 1.5-1.41 2.73-2.81 1.85c-1.41-.88-16.69-11.53-17.67-12.4c-1.41-1.23-.43-2.51.26-3.16c1.4-1.33 11.07-7.74 12.39-8.45z"
                    fill="#ffa722"></path>
                <path
                    d="M49.54 99.48c-1.77-.17-2.29 1.41-2.02 2.81c.26 1.41 2.9 19.24 3.08 20.57c.26 1.93 2.29 2.73 3.6.79s10.35-16.4 11.08-17.76c1.32-2.46.35-2.99-.97-3.6c-1.31-.61-12.92-2.63-14.77-2.81z"
                    fill="#ffa722"></path>
                <path
                    d="M24.23 79c1.23-2.02 2.81-1.49 3.96.44c.78 1.32 7.38 10.2 8 11.16c.62.97.88 2.81-1.05 3.25c-1.95.45-17.68 4.58-20.14 5.02c-2.46.44-3.87-1.49-2.29-3.6c.92-1.24 10.82-15.12 11.52-16.27z"
                    fill="#ffa722"></path>
                <path
                    d="M20.89 63.7c2.25 1 3.31.64 3.78-.97c.62-2.11 2.46-11.78 2.55-13.98c.06-1.43-.53-2.81-2.73-2.46S6.47 48.85 4.45 49.55c-2.35.82-2.18 3.4-.62 4.22c1.85.97 15.47 9.23 17.06 9.93z"
                    fill="#ffa722"></path>
                <path
                    d="M48.23 26.78c1.27-1.01.88-2.46-.26-3.25c-1.14-.79-15.26-11-17.05-12.4c-1.58-1.23-3.52-.79-2.99 2.02c.38 2.02 4.88 19.7 5.19 20.92c.35 1.41 1.41 2.11 2.64 1.23c1.21-.87 11.15-7.46 12.47-8.52z"
                    fill="#ffa722"></path>
            </svg>
    </div>
</nav>


<style>

.navbar-light {
    background: rgba(255, 255, 255, 0.97) !important;
    border-bottom: 1px solid #e5e7eb !important;
    color: #1a202c;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.03);
    padding-left: 2rem;
    padding-right: 2rem;
}

.navbar-dark {
    background: #18181b !important;
    border-bottom: 1px solid #27272a !important;
    color: #f3f4f6;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.10);
    padding-left: 2rem;
    padding-right: 2rem;
}

.navbar-brand-img {
    width: 2.2rem;
    height: 2.2rem;
    border-radius: 6px;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    object-fit: contain;
    display: inline-block;
    vertical-align: middle;
    padding: 2px;
}

.navbar-app-name {
    font-family: 'Open Sans', sans-serif;
    letter-spacing: 0.04em;
    color: inherit;
    vertical-align: middle;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Flowbite dark mode toggle script


    // Notificação dropdown
    const notifBtn = document.getElementById('notification-btn');
    const notifDropdown = document.getElementById('notification-dropdown');
    notifBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notifDropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', function(e) {
        if (!notifDropdown.classList.contains('hidden')) {
            notifDropdown.classList.add('hidden');
        }
    });
    notifDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Dark mode toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    const navbar = document.getElementById('main-navbar');

    // Atualiza ícones e navbar conforme o tema
    function updateThemeIconsAndNavbar() {
        if (document.documentElement.classList.contains('dark')) {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
            if (navbar) {
                navbar.classList.remove('navbar-light');
                navbar.classList.add('navbar-dark');
            }
        } else {
            lightIcon.classList.add('hidden');
            darkIcon.classList.remove('hidden');
            if (navbar) {
                navbar.classList.remove('navbar-dark');
                navbar.classList.add('navbar-light');
            }
        }
    }

    // Detecta preferência inicial
    if (
        localStorage.getItem('color-theme') === 'dark' ||
        (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    updateThemeIconsAndNavbar();

    // Alterna tema ao clicar
    themeToggleBtn.addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        if (document.documentElement.classList.contains('dark')) {
            localStorage.setItem('color-theme', 'dark');
        } else {
            localStorage.setItem('color-theme', 'light');
        }
        updateThemeIconsAndNavbar();
    });
});
</script>
@endpush