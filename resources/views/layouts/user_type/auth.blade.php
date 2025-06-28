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

<div class="d-flex">
    @include('layouts.navbars.auth.sidebar')
    <div class="flex-1 w-full">
        @include('layouts.navbars.auth.nav')
        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 ">
            <div class="container-fluid pt-2">
                @yield('content')
                @include('layouts.footers.auth.footer')
            </div>
        </main>
    </div>
</div>
@include('components.fixed-plugin')
@endif

@endsection

