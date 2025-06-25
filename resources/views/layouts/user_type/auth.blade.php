@extends('layouts.app')

@section('auth')


    @if(\Request::is('static-sign-up'))
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')

    @elseif (\Request::is('static-sign-in'))
        @include('layouts.navbars.guest.nav')
            @yield('content')
        @include('layouts.footers.guest.footer')

    @else
        @if (\Request::is('rtl'))
           
            <div class="d-flex">
                @include('layouts.navbars.auth.sidebar')
                 {{-- Navbar sempre no topo --}}
           
                <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg overflow-hidden">
                    <div class="container-fluid py-4">
                     @include('layouts.navbars.auth.nav')    
                    @yield('content')
                        @include('layouts.footers.auth.footer')
                    </div>
                </main>
            </div>

        @elseif (\Request::is('profile'))
           
            <div class="d-flex">
                @include('layouts.navbars.auth.sidebar')
                 {{-- Navbar sempre no topo --}}
            
                <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
                @include('layouts.navbars.auth.nav')    
                @yield('content')
                </div>
            </div>

        @else
            <div class="d-flex">
                @include('layouts.navbars.auth.sidebar')
                            {{-- Navbar sempre no topo --}}
            

                <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg{{ \Request::is('rtl') ? ' overflow-hidden' : '' }}">
                    <div class="container-fluid ">
                        @include('layouts.navbars.auth.nav')
                        @yield('content')
                        @include('layouts.footers.auth.footer')
                    </div>
                </main>
            </div>
            @include('components.fixed-plugin')
        @endif

    @endif



@endsection

<head>
    {{-- ...existing code... --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ...existing code... --}}
</head>
