@extends('layouts.app')

@section('seo')
    <title>{{ env('APP_NAME') }} - Minha conta </title>
@endsection

@section('content')
    <div class="content" id="cartPage">
        <div class="container px-5">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h1 class="h2 text-primary">Minha Conta</h1>
                    <p>
                        Bem-vindo ao painel do cliente. Aqui você poderá controlar seus pedidos.
                    </p>
                </div>
                <div class="col-md-5 text-md-right">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark btn-round btn-sm mx-2 mt-3">Voltar para o site</a>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <h2 class="h5">Meus Últimos Pedidos</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Data</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Entrega</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($orders as $order)
                            <tr>
                                <th><a href="{{ url('/pedidos/'.$order['id']) }}">{{ $order['id'] }}</a></th>
                                <th>{{ $order['created_at'] }}</th>
                                <th>R$ {{ $order['payment']['amount'] }}</th>
                                <th>{{ $order['delivery_service'] }}</th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
