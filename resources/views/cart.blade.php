@extends('layouts.app')

@section('content')

<my-cart :user="{{ json_encode(Auth::user()) }}">
</my-cart>

@endsection
