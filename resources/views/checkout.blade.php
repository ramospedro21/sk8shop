@extends('layouts.app')

@section('content')

<checkout-component :user="{{ $user }}"></checkout-component>

@endsection
