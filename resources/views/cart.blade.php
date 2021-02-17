@extends('layouts.app')

@section('content')

<my-cart :user="{{ json_encode(Auth::user()) }}" id="cartPage">
</my-cart>

@endsection
