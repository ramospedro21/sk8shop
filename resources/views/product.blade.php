
@extends('layouts.app')

@section('seo')
    <title>{{ env('APP_NAME') }} - {{ $product['title'] }}</title>
    <meta name="description" content="{{ $product['short_description'] }}">
    <meta name="keywords" content="Sk8Shop">
@endsection

@section('open-graph-products')

    <!-- OPEN GRAPH FACEBOOK E WHATSAPP -->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="SK8Shop - {{ $product['title'] }}">
    <meta property="og:description" content="{{ $product['short_description'] }}">
    @if ($product['images']->count() > 0)
        <meta property="og:image" content="{{ $product['images'][0]['url'] }}">
    @else
        <meta property="og:image" content="{{ url('images/logo_opengraph.png') }}">
    @endif
    <meta property="og:image:type" content="image/logo_opengraph.png">
    <meta property="og:image:width" content="256">
    <meta property="og:image:height" content="256">
    <meta property="og:type" content="website">
@endsection

@section('content')
    <div class="container pt-5 mt-5">
        <div class="row mt-5 mt-md-0 mb-5 align-items-center" >
            @if ($product['images'])
                <div class="col-md-1 d-none d-md-block">
                    @if($product['images']->count() > 0)
                        @foreach ($product['images'] as $key=>$image)
                            <a data-target="#productCarousel" data-slide-to="{{ $key }}" class="my-3 {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ $image['url'] }}" class="d-block w-100" alt="{{ $product['title'] }}" title="{{ $product['title'] }}">
                            </a>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-6">
                    <div id="productCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @if (count($product['images']) > 0)
                                @foreach ($product['images'] as $key=>$image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ $image['url'] }}" class="d-block w-100" alt="{{ $product['title'] }}" title="{{ $product['title'] }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="caroulse-item">
                                    <img src="{{ url('/images/no_product.png') }}" class="w-100">
                                </div>
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="col-md-3">
                    <img src="{{ url('/images/no_product.png') }}" alt="" class="w-100">
                </div>
            @endif
            <div class="col-md-5 px-5 py-5">
                <div class="d-none d-md-block">
                    <ol class="breadcrumb bg-white pl-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">
                                <small>
                                    <i class="fas fa-home"></i>
                                </small>
                            </a>
                        </li>
                        @foreach($product['breadcrumb'] as $key=>$item)
                        <li class="breadcrumb-item">
                            <a href="{{ url('c/' . $item['slug']) }}">
                                <small class="{{ $key == (count($product['breadcrumb']) - 1) ? 'font-weight-bold' : '' }}">{{ $item['title']}}</small>
                            </a>
                        </li>
                        @endforeach
                    </ol>
                </div>
                <hr>
                <h1 class="font-weight-bolder h3">{{ $product['title'] }}</h1>
                <h2 class="h6">{{ $product['short_description'] }}</h2>

                <p class="h2 text-wine mb-0 mt-4" id="productPrice">
                    @if($product['stocks'][0]['promote_price'] === '0.00')
                        R$ {{ number_format($product['stocks'][0]['price'], 2, ',', '.') }}
                        <p class="h6 mb-0">
                            <small class="text-info">até {{ $product['installments'] }} x <span id="productInstallmentPrice">R$ {{ number_format( ($product['stocks'][0]['price'] / $product['installments']), 2, ',', '.') }}</span></small>
                        </p>
                    @else
                        De <strike>R$ {{ number_format($product['stocks'][0]['price'], 2, ',', '.') }}</strike>
                        <br>
                        <span class="text-success">Por R$ {{ number_format($product['stocks'][0]['promote_price'], 2, ',', '.') }}</span>
                        <p class="h6 mb-0">
                            <small class="text-info">até {{ $product['installments'] }} x <span id="productInstallmentPrice">R$ {{ number_format( ($product['stocks'][0]['promote_price'] / $product['installments']), 2, ',', '.') }}</span></small>
                        </p>
                    @endif
                </p>

                <buy-product-component :product="{{ $product }}"
                                       :variants="{{ $variants }}"
                                       :options="{{ $options }}"
                                       :slug="'{{ $product['slug'] }}'">
                </btn-buy-product>

            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 mt-5 mt-md-0">
                <h2 class="text-uppercase h5 font-weight-bold">
                    <i class="fas fa-wine-bottle mr-3 text-secondary"></i>
                    Produtos Similares
                </h2>
            </div>
        </div>
        <div class="row">
                @foreach ($similars as $product)
                    @include('components.product', ['product' => $product, 'cols' => 4])
                @endforeach
        </div>
    </div>

@endsection
