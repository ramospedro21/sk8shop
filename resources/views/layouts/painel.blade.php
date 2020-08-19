<!doctype html>
<html lang="en">
  <head>
    <title>@yield('titulo')</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />


    <!-- Fav Icon -->


    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" crossorigin="anonymous">

    <!-- Styles -->
    @yield('styles')
    <link href="{{ mix('css/painel.css') }}" rel="stylesheet">

  </head>
  <body>

    <div id="app">
        <div class="wrapper">
            <div class="sidebar" data-color="purple" data-background-color="white">
              <div class="logo py-0 mt-4">
                <a href="{{ url('/') }}" class="simple-text logo-normal py-0" >
                    <img src="{{ url('images/logo.jpg') }}" alt="{{ config('app.name') }}" 
                    title="{{ config('app.name') }}">
                </a>
              </div>

              <div class="sidebar-wrapper">
                <ul class="nav">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">
                            <i class="fas fa-home"></i>
                            <p> Home </p>
                        </a>
                    </li>
                    @foreach ($modules as $module)
                        <li class="nav-item">
                            <a class="nav-link text-white" data-toggle="collapse" href="#module{{ $module['id'] }}" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="module{{ $module['id'] }}">
                                <i class="{{ $module['icon'] }}"></i>
                                <p> {{ $module['name'] }}</p>
                            </a>
                            @foreach ($module['values'] as $value)
                                <div class="collapse pl-4 mt-2" id="module{{ $value['module_id'] }}">
                                    <ul class="navbar-nav">
                                        <li class="nav-item align-items-center">
                                            <a class="nav-link" href="{{ $value['link'] }}">
                                                <i class="{{ $value['icon'] }} mt-1"></i>
                                                <p> {{ $value['name'] }} </p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach
                        </li>
                    @endforeach
                </ul>

              </div>
            </div>
            <div class="main-panel">
              <!-- Navbar -->
              <nav class="navbar navbar-expand-lg navbar-absolute fixed-top panel">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand">@yield('page-title')</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false">
                                    <i class="fas fa-user mr-2"></i>
                                    <span>Olá, <b>{{ Auth::user()->name }}</b></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a href="{{ route('logout') }}" class="dropdown-item"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        Sair
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" 
                                    style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
              </nav>
              <!-- End Navbar -->
              <div class="px-md-5">
                @yield('content')
              </div>
        </div>
    </div>

</div>

    <!-- Scripts -->
    <script src="{{ mix('js/painel.js') }}"></script>
    @yield('scripts')

  </body>
</html>
