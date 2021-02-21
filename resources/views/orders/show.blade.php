@extends('layouts.painel')

@section('titulo')
  Pedido Nº {{ $order->id }}
@endsection

@section('content')
	<order-index-component :_order="{{ $order }}"> </order-index-component>
@endsection
