@extends('layouts.painel')

@section('titulo')
  Usuário: {{ $user->name }}
@endsection


@section('content')
    <users-show-component :_user="{{ json_encode($user) }}"> </users-show-component>
@endsection