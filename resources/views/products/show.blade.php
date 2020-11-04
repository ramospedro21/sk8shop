@extends('layouts.painel')

@section('titulo')
  Produto
@endsection

@section('content')
    <product-component 
        :_product="{{ json_encode($product) }}">
    </product-component>
@endsection