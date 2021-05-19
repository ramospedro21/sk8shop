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
    <meta property="og:image" content="{{ url('images/logo_opengraph.png') }}">
    <meta property="og:image:width" content="256">
    <meta property="og:image:height" content="256">
    <meta property="og:image:type" content="image/logo_opengraph.png">
    <meta property="og:type" content="website">
@endsection

@section('content')

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-8">
            <h3 class="h3 font-weight-bold text-center">Sobre a SK8Shop</h3>
            <p class="h4 mt-3 text-justify"> Fundada em outubro de 2013, a Sk8Shop é um sistema de lojas que atua no
                segmento moda urbana tendo como raiz o skate. Nossa missão é agregar valor
                ao mercado atuação e influenciar de forma positiva pessoas e regiões.
            </p>
            <p class="h4 mt-3 text-justify">
                Após 7 anos de atuação no varejo com lojas físicas, iniciamos nossa operação
                online com o objetivo de para ampliar e melhorar a experiência de compra para
                nossos clientes.
            </p>
        </div>
    </div>
</div>

@endsection
