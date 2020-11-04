@extends('layouts.app')

@section('content')
    <div class="container pt-5">
        <div class="row mt-5 bg-pigments">
            <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                <form class="form" method="POST" action="{{ 'login' }}">
                @csrf
                <div class="card">
                    <div class="card-header-login py-2 text-center">
                        <img src="{{ url('images/logo.jpg') }}" alt="">
                    </div>
                    <div class="card-body ">
                        <span class="bmd-form-group">
                            <label for="" class="form-control-label">Email:</label>
                            <div class="input-group">
                                <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" name="email" placeholder="Digite seu email" required  autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert" >
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </span>
                    <br>
                        <label for="" class="form-control-label">Senha:</label>
                        <span class="bmd-form-group">
                            <div class="input-group">
                                <input id="password" type="password" placeholder="Digite a sua senha" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </span>
                    </div>
                    <div class="card-footer mt-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <a href="{{ url('/forgot_password') }}">
                                    Esqueceu a senha?
                                </a>
                            </div>
                            <div class="col text-right">
                                <button type="submit" class="btn btn-success">Entrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
