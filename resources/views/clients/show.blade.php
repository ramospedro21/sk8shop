@extends('layouts.painel')

@section('titulo')
  Cliente {{ $client->name }}
@endsection

@section('content')
	<client-show-component :_client="{{ json_encode($client) }}"> </client-show-component>
@endsection