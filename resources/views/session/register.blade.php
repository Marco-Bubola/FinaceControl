@extends('layouts.user_type.guest')

@section('content')

    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100 align-items-start pt-5 ">
                <div class="container-fluid">
                    <div class="ring">
                        <i style="--clr:#00ff0a;"></i>
                        <i style="--clr:#ff0057;"></i>
                        <i style="--clr:#fffd44;"></i>
                        <div class="login">
                            <h2>Registrar</h2>

                            <!-- Social login buttons moved here -->
                            <div class="row  ">
                                <div class="col-4">
                                    <a class="btn btn-outline-light w-100" href="javascript:;">
                                        <!-- Facebook Icon -->
                                        <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink32">
                                            <g id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="facebook-3" transform="translate(3.000000, 3.000000)"
                                                    fill-rule="nonzero">
                                                    <circle id="Oval" fill="#3C5A9A" cx="29.5091719" cy="29.4927506"
                                                        r="29.4882047"></circle>
                                                    <path
                                                        d="M39.0974944,9.05587273 L32.5651312,9.05587273 C28.6886088,9.05587273 24.3768224,10.6862851 24.3768224,16.3054653 C24.395747,18.2634019 24.3768224,20.1385313 24.3768224,22.2488655 L19.8922122,22.2488655 L19.8922122,29.3852113 L24.5156022,29.3852113 L24.5156022,49.9295284 L33.0113092,49.9295284 L33.0113092,29.2496356 L38.6187742,29.2496356 L39.1261316,22.2288395 L32.8649196,22.2288395 C32.8649196,22.2288395 32.8789377,19.1056932 32.8649196,18.1987181 C32.8649196,15.9781412 35.1755132,16.1053059 35.3144932,16.1053059 C36.4140178,16.1053059 38.5518876,16.1085101 39.1006986,16.1053059 L39.1006986,9.05587273 L39.0974944,9.05587273 L39.0974944,9.05587273 Z"
                                                        id="Path" fill="#FFFFFF"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a class="btn btn-outline-light w-100" href="javascript:;">
                                        <!-- Apple Icon -->
                                        <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="apple-black" transform="translate(7.000000, 0.564551)" fill="#000000"
                                                    fill-rule="nonzero">
                                                    <path
                                                        d="M40.9233048,32.8428307 C41.0078713,42.0741676 48.9124247,45.146088 49,45.1851909 C48.9331634,45.4017274 47.7369821,49.5628653 44.835501,53.8610269 C42.3271952,57.5771105 39.7241148,61.2793611 35.6233362,61.356042 C31.5939073,61.431307 30.2982233,58.9340578 25.6914424,58.9340578 C21.0860585,58.9340578 19.6464932,61.27947 15.8321878,61.4314159 C11.8738936,61.5833617 8.85958554,57.4131833 6.33064852,53.7107148 C1.16284874,46.1373849 -2.78641926,32.3103122 2.51645059,22.9768066 C5.15080028,18.3417501 9.85858819,15.4066355 14.9684701,15.3313705 C18.8554146,15.2562145 22.5241194,17.9820905 24.9003639,17.9820905 C27.275104,17.9820905 31.733383,14.7039812 36.4203248,15.1854154 C38.3824403,15.2681959 43.8902255,15.9888223 47.4267616,21.2362369 C47.1417927,21.4153043 40.8549638,25.1251794 40.9233048,32.8428307 M33.3504628,10.1750144 C35.4519466,7.59650964 36.8663676,4.00699306 36.4804992,0.435448578 C33.4513624,0.558856931 29.7884601,2.48154382 27.6157341,5.05863265 C25.6685547,7.34076135 23.9632549,10.9934525 24.4233742,14.4943068 C27.7996959,14.7590956 31.2488715,12.7551531 33.3504628,10.1750144"
                                                        id="Shape"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a class="btn btn-outline-light w-100" href="{{ route('google-auth') }}">
                                        <!-- Google Icon -->
                                        <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1"
                                            xmlns="http://www.w3.org/1999/xlink">
                                            <g id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="google-icon" transform="translate(3.000000, 2.000000)"
                                                    fill-rule="nonzero">
                                                    <path
                                                        d="M57.8123233,30.1515267 C57.8123233,27.7263183 57.6155321,25.9565533 57.1896408,24.1212666 L29.4960833,24.1212666 L29.4960833,35.0674653 L45.7515771,35.0674653 C45.4239683,37.7877475 43.6542033,41.8844383 39.7213169,44.6372555 L39.6661883,45.0037254 L48.4223791,51.7870338 L49.0290201,51.8475849 C54.6004021,46.7020943 57.8123233,39.1313952 57.8123233,30.1515267"
                                                        id="Path" fill="#4285F4"></path>
                                                    <path
                                                        d="M29.4960833,58.9921667 C37.4599129,58.9921667 44.1456164,56.3701671 49.0290201,51.8475849 L39.7213169,44.6372555 C37.2305867,46.3742596 33.887622,47.5868638 29.4960833,47.5868638 C21.6960582,47.5868638 15.0758763,42.4415991 12.7159637,35.3297782 L12.3700541,35.3591501 L3.26524241,42.4054492 L3.14617358,42.736447 C7.9965904,52.3717589 17.959737,58.9921667 29.4960833,58.9921667"
                                                        id="Path" fill="#34A853"></path>
                                                    <path
                                                        d="M12.7159637,35.3297782 C12.0932812,33.4944915 11.7329116,31.5279353 11.7329116,29.4960833 C11.7329116,27.4640054 12.0932812,25.4976752 12.6832029,23.6623884 L12.6667095,23.2715173 L3.44779955,16.1120237 L3.14617358,16.2554937 C1.14708246,20.2539019 0,24.7439491 0,29.4960833 C0,34.2482175 1.14708246,38.7380388 3.14617358,42.736447 L12.7159637,35.3297782"
                                                        id="Path" fill="#FBBC05"></path>
                                                    <path
                                                        d="M29.4960833,11.4050769 C35.0347044,11.4050769 38.7707997,13.7975244 40.9011602,15.7968415 L49.2255853,7.66898166 C44.1130815,2.91684746 37.4599129,0 29.4960833,0 C17.959737,0 7.9965904,6.62018183 3.14617358,16.2554937 L12.6832029,23.6623884 C15.0758763,16.5505675 21.6960582,11.4050769 29.4960833,11.4050769"
                                                        id="Path" fill="#EB4335"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Form for Registration -->
                            <form role="form" method="POST" action="/register">
                                @csrf
                                <div class="inputBx">
                                    <label for="name">Nome</label>
                                    <input type="text" name="name" id="name" placeholder="Nome" value="{{ old('name') }}"
                                        aria-label="Name" aria-describedby="name-addon">
                                    @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="inputBx">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" placeholder="Email"
                                        value="{{ old('email') }}" aria-label="Email" aria-describedby="email-addon">
                                    @error('email')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="inputBx">
                                    <label for="password">Senha</label>
                                    <input type="password" name="password" id="password" placeholder="Senha"
                                        value="{{ old('password') }}" aria-label="Password"
                                        aria-describedby="password-addon">
                                    @error('password')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-check form-check-info text-left">
                                    <input class="form-check-input" type="checkbox" name="agreement" id="flexCheckDefault"
                                        checked>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        eu concordo com os <a href="javascript:;" class="text-dark font-weight-bolder">Termos e
                                            Condições</a>
                                    </label>
                                    @error('agreement')
                                        <p class="text-danger text-xs mt-2">Primeiro, concorde com os Termos e Condições e tente
                                            registrar-se novamente.</p>
                                    @enderror
                                </div>

                                <div class="inputBx">
                                    <input type="submit" value="Registrar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

<style>
    @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Quicksand", sans-serif;
    }

    body {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;

        align-items: center;
        min-height: 100vh;
        background: #111;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .page-header {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        margin-top: 30px;
    }

    .ring {
        position: relative;
        width: 800px; /* Alterado para 100% para se ajustar à tela */
        max-width: 800px; /* Limita o tamanho máximo */
        height: 800px; /* Tamanho fixo para os anéis */
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left: 100px; /* Ajustando a margem esquerda */

margin-right: 100px;
    }

    .ring i {
        position: absolute;
        inset: 0;
        border: 2px solid #2e2e2e;
        transition: 0.5s;

    }

    .ring i:nth-child(1) {
        border-radius: 38% 62% 63% 37% / 41% 44% 56% 59%;
        animation: animate 6s linear infinite;
    }

    .ring i:nth-child(2) {
        border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
        animation: animate 4s linear infinite;
    }

    .ring i:nth-child(3) {
        border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
        animation: animate2 10s linear infinite;
    }

    .ring:hover i {
        border: 6px solid var(--clr);
        filter: drop-shadow(0 0 20px var(--clr));
    }

    @keyframes animate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes animate2 {
        0% {
            transform: rotate(360deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .login {
        position: absolute;
        width: 350px;
        height: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 20px;
        top: 50%;
        transform: translateY(-50%);
        overflow-x: hidden;
    }

    .login h2 {
        font-size: 2em;
        color: #000000;
    }

    .login .inputBx {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
    }

    .login .inputBx input {
        position: relative;
        width: 100%;
        padding: 12px 20px;
        background: transparent;
        border: 2px solid #000000;
        border-radius: 40px;
        font-size: 1.2em;
        color: #000000;
        box-shadow: none;
        outline: none;
    }

    .login .inputBx input[type="submit"] {
        width: 100%;
        background: linear-gradient(45deg, #ff357a, #fff172);
        border: none;
        cursor: pointer;
        margin-top: 20px;
    }

    .login .inputBx input::placeholder {
        color: rgba(255, 255, 255, 0.75);
    }

    .login .links {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .login .links a {
        color: #000000;
        text-decoration: none;
    }

    /* Adicionando estilo para o checkbox e corrigindo o bug */
    .login .form-check-input {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    .login .form-check-label {
        font-size: 1em;
        color: #000;
    }
</style>
