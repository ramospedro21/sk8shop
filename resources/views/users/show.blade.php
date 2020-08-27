@extends('layouts.painel')

@yield('Usuários')

@section('content')
    <users-show-component :_user="{{ json_encode($user) }}"> </users-show-component>
@endsection