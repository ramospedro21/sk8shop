
@extends('layouts.app')

@section('seo')
    <title> 404 :( </title>
    <meta name="description" content="Nada foi encontrado.">
    <meta name="keywords" content="Sk8Shop">
@endsection

@section('open-graph-products')
    <!-- OPEN GRAPH FACEBOOK E WHATSAPP -->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="SK8Shop - 404">
    <meta property="og:description" content="Pagina de erro 404">
    <meta property="og:image" content="{{ url('images/logo_opengraph.png') }}">
    <meta property="og:image:type" content="image/logo_opengraph.png">
    <meta property="og:image:width" content="256">
    <meta property="og:image:height" content="256">
    <meta property="og:type" content="website">
@endsection

@section('content')

<div class="container my-md-5" id="cartPage">
    <div class="row align-items-center">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <p class="h3 text-uppercase">a página solicitada ou o produto procurando não foi encontrado(a).</p>
            </div>
        </div>
    </div>
</div>

@endsection
