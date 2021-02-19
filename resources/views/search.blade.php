@extends('layouts.app')

@section('content')

<div class="container my-md-5" id="cartPage">
    <div class="row align-items-center">
        <div class="col-md-3 mt-5 mt-md-0">
            <h1 class="text-uppercase h4 font-weight-bold">
                {{ $search }}
            </h1>
            <h2 class="h6 my-3">
                Produto pesquisado
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        @if (count($products) > 0)
            <div class="col-md-9">
                <div class="row">
                    @foreach($products as $product)
                    @include('components.product', ['product' => $product, 'cols' => 3])
                    @endforeach
                </div>
            </div>
        @else
            <div class="col-md-9">
                <div class="row justify-content-center">
                    <p class="h4">Nenhum produto encontrado.</p>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
