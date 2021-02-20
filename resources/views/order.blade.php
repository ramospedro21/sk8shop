@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="row py-5 align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 text-primary">Pedido Nº{{ $order['id'] }}</h1>
                    <p>
                        Painel do produto. Aqui você consegue visualizar maiores informações sobre o seu pedido
                    </p>
                </div>
                <div class="col-md-5 text-md-right">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark btn-round btn-sm mx-2 mt-3">Voltar para o site</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card bg-light shadow">
                        <div class="card-header bg-white border-0">
                            <div class="col-md-8">
                                <h3 class="mb-0">Produtos</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach ($order['products'] as $product)
                            <div class="row align-items-center mb-4">
                                <div class="col-5 mb-3 mb-md-0">
                                    <img src="{{ $product['variant']['images'][0]['url'] }}" class="img-fluid">
                                </div>
                                <div class="col-7 mb-3 mb-md-0">
                                    <p class="h4">{{ $product['title'] }}</p>
                                    <p class="h6">SKU: {{ $product['variant']['sku'] }}</p>
                                    <hr class="my-2">
                                    <p class="mb-0">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow ">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Entrega</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="h4">
                                {{ $order['delivery_code'] }} - {{ $order['delivery_service'] }}
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        CEP: {{ $order['zipcode'] }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Logradouro: {{ $order['street'] }}, {{ $order['street_number'] }} - {{ $order['complement'] }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Bairro: {{ $order['district'] }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Cidade: {{ $order['city'] }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Estado: {{ $order['state'] }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Valor: R$ {{ $order['payment']['shipping_price'] }}
                                    </span>
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="card bg-light shadow mt-4">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Financeiro</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Forma de Pagamento:
                                        {{ $order['payment']['gateway_brand'] == 'boleto' ? 'Boleto' : '' }}
                                        {{ $order['payment']['gateway_brand'] == 'credit-card' ? 'Cartão de Crédito' : '' }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Status:
                                        {{ $order['payment']['gateway_status'] == 'WAITING' ? 'Aguardando Aprovação' : '' }}
                                        {{ $order['payment']['gateway_status'] == 'AUTHORIZED' ? 'Aprovado' : '' }}
                                        {{ $order['payment']['gateway_status'] == 'CANCELLED' ? 'Cancelado' : '' }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Parcelas: {{ $order['payment']['installments'] }}
                                    </span>
                                </small>
                            </p>
                            <p class="mb-0 py-2">
                                <small>
                                    <span class="h5 mr-2">
                                        Valor: R$ {{ $order['payment']['amount'] }}
                                    </span>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
