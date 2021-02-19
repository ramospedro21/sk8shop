@extends('layouts.app')

@section('content')

<div id="cartPage">
    <checkout-component :user="{{ $user }}" :moip_pub_key="{{ json_encode(env('MOIP_PUBLIC_KEY')) }}"></checkout-component>
</div>

@endsection
