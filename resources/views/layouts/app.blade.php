<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ env('APP_NAME') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" crossorigin="anonymous">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ url('images/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ url('images/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ url('images/favicon-16x16.png') }}">

    </head>
    <body>
        <div id="app" class="pb-5 pb-md-0 mb-3 mb-md-0 pt-4 mt-3">
            {{-- <div class="container-fluid border-bottom px-5 fixed-top bg-white shadow-sm d-none d-md-block" id="menu">
                <div class="row align-items-center px-5">
                    <div class="col-md-3">
                        <a class="" href="{{ url('/') }}">
                            <img src="{{ url('images/logo.png') }}" alt="Sk8Shop" class="w-100">
                        </a>
                    </div>
                    <div class="col-md-9 text-right">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a class="nav-link" href="{{ url('/') }}">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            @foreach ($categories as $category)
                            <li class="list-inline-item dropdown custom-dropdown px-2 py-3" data-target="#category{{ $category['id'] }}">
                                <a class="nav-link dropdown-toggle" href="{{ url( '/c/' . $category['slug']) }}">
                                    {{ $category['title'] }}
                                </a>
                                <div class="dropdown-menu custom-dropdown-menu my-0 py-4 shadow-sm" aria-labelledby="category{{ $category['id'] }}" id="category{{ $category['id'] }}">
                                    <div class="container">
                                        <div class="row">
                                            @foreach ($category['children'] as $child)
                                            <div class="col-md-3">
                                                <a href="{{ url( '/c/' . $child['slug']) }}" class="h5 dropdown-item">
                                                    <small>{{ $child['title'] }}</small>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                @foreach ($child['children'] as $lastChild)
                                                <a href="{{ url( '/c/' . $lastChild['slug']) }}" class="h6 dropdown-item mb-0">
                                                    <small>{{ $lastChild['title'] }}</small>
                                                </a>
                                                @endforeach
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            <li class="list-inline-item px-2">
                                <a class="nav-link" href="#" data-target="#searchModal" data-toggle="modal">
                                    <i class="fas fa-search mr-2"></i> Buscar
                                </a>
                            </li>
                            @if(Auth::user())
                            <li class="list-inline-item dropdown-account custom-dropdown" data-target="#dropdownMenuButton">
                                <button class="btn btn-outline text-primary dropdown-toggle" type="button">
                                        <i class="fas fa-user mr-2"></i>
                                </button>
                                <div class="dropdown-menu custom-dropdown-menu " id="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ url('/minha-conta') }}">Minha Conta</a>
                                    <a class="dropdown-item" href="{{ url('/logout') }}">Sair</a>
                                </div>
                            </li>
                            @else
                            <li class="list-inline-item px-2">
                                <a class="h6 text-secondary" href="#" data-target="#accountModal" data-toggle="modal">
                                    <i class="fas fa-user mr-2"></i> Cadastre-se | Login
                                </a>
                            </li>
                            @endauth
                            <li class="list-inline-item pl-2">
                                <a class="nav-link" :href="'/carrinho'">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    <span class="text-primary font-weight-bold">
                                        {{ Session::get('cart.cartCount') ? Session::get('cart.cartCount') : 0 }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> --}}
            <div class="container-fluid border-bottom px-5 fixed-top bg-white shadow-sm d-none d-md-block" id="menu">
                <div class="row align-items-center px-5">
                    <div class="col-md-3 text-right">
                        <a href="{{ url('/') }}">
                            <img src="{{ url('images/logo_sk8shop_correta.jpg') }}" class="">
                        </a>
                    </div>
                    <div class="col-md-6 text-center mt-2">
                        <ul class="list-inline mb-0 pb-0 text-uppercase">
                            @foreach ($categories as $category)
                            <li class="list-inline-item dropdown custom-dropdown mb-2" data-target="#category{{ $category['id'] }}">
                                <a class="nav-link dropdown-toggle text-category" href="{{ url( '/category/' . $category['slug']) }}">
                                    {{ $category['title'] }}
                                </a>
                                <div class="dropdown-menu custom-dropdown-menu my-0 py-4 shadow-sm" aria-labelledby="category{{ $category['id'] }}" id="category{{ $category['id'] }}">
                                    <div class="container">
                                        <div class="row">
                                            @foreach ($category['children'] as $child)
                                            <div class="col-md-3">
                                                <a href="{{ url( '/category/' . $child['slug']) }}" class="h5 dropdown-item">
                                                    <small class="font-weight-bold">{{ $child['title'] }}</small>
                                                </a>
                                                @foreach ($child['children'] as $lastChild)
                                                    <a href="{{ url( '/category/' . $lastChild['slug']) }}" class="h6 dropdown-item">
                                                        <small>{{ $lastChild['title'] }}</small>
                                                    </a>
                                                @endforeach
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-3">
                        @if(Auth::user())
                            <li class="list-inline-item dropdown-account custom-dropdown" data-target="#dropdownMenuButton">
                                <button class="btn btn-outline dropdown-toggle" type="button">
                                        <i class="fas fa-user mr-2 "></i>
                                </button>
                                <div class="dropdown-menu custom-dropdown-menu " id="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ url('/minha-conta') }}">Minha Conta</a>
                                    <a class="dropdown-item" href="{{ url('/logout') }}">Sair</a>
                                </div>
                            </li>
                        @else
                            <li class="list-inline-item">
                                <a class="h6 mx-2 py-3" href="#" data-target="#accountModal" data-toggle="modal">
                                    <i class="fas fa-user"></i>
                                </a>
                            </li>
                        @endif
                        <li class="list-inline-item">
                            <a class="nav-link" :href="'/carrinho'">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="py-3 font-weight-bold">
                                    {{ Session::get('cart.cartCount') ? Session::get('cart.cartCount') : 0 }}
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="nav-link" href="#" data-target="#searchModal" data-toggle="modal">
                                <i class="fas fa-search mr-2"></i> Buscar
                            </a>
                        </li>
                    </div>
                </div>
            </div>

            <div class="fixed-top d-md-none bg-light border-bottom" id="header-mobile">
                <div class="container px-0 py-3">
                    <div class="row">
                        <div class="col px-5">
                            <a class="my-0" href="{{ url('/') }}">
                                <img src="{{ url('images/logo.png') }}" height="35" alt="">
                            </a>
                        </div>
                        <div class="col px-5 text-right">
                            <a class="btn btn-link btn-lg" href="#" data-target="#searchModal" data-toggle="modal">
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-5 py-md-0">

                @yield('content')

            </div>

            <div class="fixed-bottom d-md-none bg-white" id="menu-mobile">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <ul class="nav align-items-center justify-content-center">
                                <li class="nav-item">
                                    <a class="nav-link px-4 py-4 h4 my-0 text-dark ml-2" href="{{ url('/') }}">
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    @if(Auth::user())
                                        <a class="nav-link px-4 py-4 h4 my-0 text-dark" href="{{ url('/minha-conta') }}">
                                            <i class="fas fa-user"></i>
                                        </a>
                                    @else
                                        <a class="nav-link px-4 py-4 h4 my-0 text-dark" href="#" data-target="#accountModal" data-toggle="modal">
                                            <i class="fas fa-user"></i>
                                        </a>
                                    @endauth
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-4 py-3 h4 my-0 text-dark" href="{{ url('/carrinho') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.account-modal')
            @include('components.search-modal')
            {{-- @include('components.menu-modal')
            @include('components.cupom-modal') --}}

        </div>

        <script src="{{ mix('js/app.js')}}"></script>

    </body>
</html>
