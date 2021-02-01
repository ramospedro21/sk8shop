@extends('layouts.app')

@section('seo')
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="Confira o que há de melhor em roupas e skate">
    <meta name="keywords" content="skate, roupa, tenis, camisa, calça">
@endsection

@section('open-graph')

    <!-- OPEN GRAPH FACEBOOK E WHATSAPP -->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Sk8Shop">
    <meta property="og:description" content="Pegar descrição com guilherme.">
    <meta property="og:image" content="{{ url('images/logo_opengraph.png') }}">
    <meta property="og:image:width" content="256">
    <meta property="og:image:height" content="256">
    <meta property="og:image:type" content="image/logo_opengraph.png">
    <meta property="og:type" content="website">
@endsection

@section('content')

<div id="bannersCarousel" class="carousel slide mb-5 d-none d-md-block" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <a href="{{url('/c/espumantesoJS')}}">
                <img src="{{ url('images/banner_promoJAN.png') }}" class="w-100" alt="">
            </a>
        </div>
    </div>
    {{-- <a class="carousel-control-prev" href="#bannersCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#bannersCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a> --}}
</div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div id="bannersCarousel" class="carousel slide mb-5 d-none d-md-block" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row ml-3">
                        <div class="col">
                            <a href="{{url('/c/espumantesoJS')}}">
                                <img src="{{ url('images/logoAdidas.png') }}" class="w-50" alt="">
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{url('/c/espumantesoJS')}}">
                                <img src="{{ url('images/logoNike.png') }}" class="w-50" alt="">
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{url('/c/espumantesoJS')}}">
                                <img src="{{ url('images/logoPrimitive.png') }}" class="w-50" alt="">
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{url('/c/espumantesoJS')}}">
                                <img src="{{ url('images/logoVans.png') }}" class="w-50" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <a class="carousel-control-prev" href="#bannersCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#bannersCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a> --}}
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        @foreach ($products as $product)
            
            @include('components.product', ['product' => $product, 'cols' => 4])

        @endforeach
    </div>
</div>

@endsection