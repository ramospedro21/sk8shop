
@if(!Session::get('token'))
<!-- Modal -->
<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header px-0 py-0">
                <ul class="nav nav-pills w-100" id="pills-tab" role="tablist">
                    <li class="nav-item w-50 text-center">
                        <a class="nav-link rounded-0 py-3 active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Tenho Conta</a>
                    </li>
                    <li class="nav-item w-50 text-center">
                        <a class="nav-link rounded-0 py-3" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Criar Conta</a>
                    </li>
                </ul>
            </div>
            <div class="modal-body py-4 bg-light">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                        <form class="form" method="POST" action="{{ url('/login/site') }}">
                            @csrf

                            <p class="font-weight-bold h5"><i class="fas fa-user mr-3 text-primary"></i>Login</p>
                            <hr>
                            <div class="form-group mt-3">
                                <label for="" class="control-label">E-mail:</label>
                                <input type="text" name="email" class="form-control py-4" placeholder="Ex.: email@email.com" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Senha:</label>
                                <input type="password" name="password" class="form-control py-4" placeholder="Digite sua senha..." required>
                            </div>
                            <button class="btn btn-primary btn-block text-white btn-lg mb-3">
                                Continuar
                            </button>
                            <a href="" class="text-info">Esqueceu a senha?</a>

                        </form>

                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        <form method="POST" action="{{ url('register') }}">
                            @csrf
                            <p class="font-weight-bold h5"><i class="fas fa-user mr-3 text-primary"></i>Cadastre-se</p>
                            <hr>
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Nome:</label>
                                <input name="name" type="text" class="form-control py-4" placeholder="Digite seu nome.." required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="" class="control-label">E-mail:</label>
                                <input name="email" type="text" class="form-control py-4" placeholder="Ex.: email@email.com" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Senha:</label>
                                <input name="password" type="password" class="form-control py-4" placeholder="Digite sua senha..." required>
                            </div>
                            <div class="form-group form-check">
                                <input name="terms" type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label text-info" for="terms">
                                    <small>Li e concordo com a <a target="_blank" href="{{ url('/docs/POLITICA_DE_PRIVACIDADE.pdf') }}">política de privacidade</a></small>
                                </label>
                            </div>
                            <button class="btn btn-primary btn-block text-white btn-lg" type="submit">
                                Continuar
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
