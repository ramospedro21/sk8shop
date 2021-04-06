<template>

    <div class="container mt-5 pt-md-0 py-5">
        <div class="row" v-if="success == 0 && error == 0">
            <div class="col-8">
                <h1 class="font-weight-bolder h5 mt-5">Frete e Prazo de Entrega</h1>
                <div class="card pt-4 pb-3 px-4 my-4">
                    <form @submit.prevent="calculateDelivery()" v-if="cart.shippings.length == 0 || cart.shippings == undefined && this.calculating == false ">
                        <div class="row mt-3 mb-5 mb-md-3 align-items-center">
                            <div class="col-md-4 col-8">
                                <input type="text" class="form-control" placeholder="Digite seu CEP aqui.." v-model="cart.zipcodeTo" id="zipcodeInput" required>
                            </div>
                            <div class="col-md-2 col-4">
                                <button class="btn btn-light btn-block" type="submit">
                                    Calcular
                                </button>
                            </div>
                            <div class="col d-none d-md-block">
                                <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/buscaCepEndereco.cfm" target="_blank" class="text-info">
                                    <small>Não sei meu CEP</small>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <small class="text-danger text-left" v-if="errors.zipcodeTo">{{ errors.zipcodeTo }}</small>
                            </div>
                        </div>
                    </form>
                    <div v-else-if="cart.shippings.length > 0 && this.calculating == false" class="form-check form-check-radio mt-2 col-md-12 pl-5" v-for="(shipping, i) in cart.shippings" :key="i">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" v-model="cart.shipping" :id="'shipping' + i" :value="shipping" v-on:change="selectShipping()">
                            <p class="mb-0 pb-0">{{ shipping.title }}</p>
                            <input v-model.lazy="shipping.price" readonly type="hidden"/>
                            <p class="mb-0 pb-0">{{ shipping.price | money}} - {{ shipping.deadline }}</p>
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div v-else-if="this.calculating == true">
                        Calculando...
                    </div>
                </div>
            </div>
        </div>


        <div class="row" v-if="success == 0 && error == 0">
            <div class="col-8 mt-5">
                <h2 class="font-weight-bolder h5">Informações para entrega</h2>
                <div class="card pt-4 pb-3 px-4 my-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Nome:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite seu nome.." v-model="user.name">
                                <small class="text-danger" v-if="errors.name">{{ errors.name }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">CPF.:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite seu CPF.." v-model="userDetails.tax_document_number" v-mask="'999.999.999-99'" @change="validateInfoCPF(userDetails.tax_document_number)">
                                <small class="text-danger" v-if="errors.tax_document_number">{{ errors.tax_document_number }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Nascimento:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite seu nascimento.." v-model="userDetails.birthdate" v-mask="'99/99/9999'">
                                <small class="text-danger" v-if="errors.birthdate">{{ errors.birthdate }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Celular:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite seu celular.." v-model="userDetails.cellphone" v-mask="'(99) [9]9999-9999'">
                                <small class="text-danger" v-if="errors.cellphone">{{ errors.cellphone }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-8 col-md-4">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">CEP:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite seu CEP.." v-model="userAddress.zipcode" v-mask="'99999-999'" v-on:blur="getAddress(userAddress.zipcode)">
                                <small class="text-danger" v-if="errors.zipcode">{{ errors.zipcode }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Endereço:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite seu endereço.." v-model="userAddress.street">
                                <small class="text-danger" v-if="errors.street">{{ errors.street }}</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Nº:</label>
                                <input type="text" class="form-control py-4" placeholder="Ex.: 123" v-model="userAddress.street_number">
                                <small class="text-danger" v-if="errors.street_number">{{ errors.street_number }}</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Complemento:</label>
                                <input type="text" class="form-control py-4" placeholder="Ex.: Apto 123" v-model="userAddress.complement">
                                <small class="text-danger" v-if="errors.complement">{{ errors.complement }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Bairro:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite seu bairro.." v-model="userAddress.district">
                                <small class="text-danger" v-if="errors.district">{{ errors.district }}</small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Cidade:</label>
                                <input type="text" class="form-control py-4" placeholder="Digite sua cidade.." v-model="userAddress.city">
                                <small class="text-danger" v-if="errors.city">{{ errors.city }}</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mt-3">
                                <label for="" class="control-label">Estado:</label>
                                <input type="text" class="form-control py-4" placeholder="UF" v-model="userAddress.state" v-mask="'AA'">
                                <small class="text-danger" v-if="errors.state">{{ errors.state }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="font-weight-bolder h">Pagamento</h2>
                <div class="card pt-4 pb-3 px-4 my">
                    <div class="row align-items-center">
                        <div class="col">
                            <button class="btn btn-primary text-white btn-block btn-lg" @click="setPayment('barcode')">
                                <i class="fas fa-barcode mr-3"></i>Boleto
                            </button>
                        </div>
                    </div>
                    <div class="row my-3" v-if="userPayment.type == 'barcode'">
                        <div class="col">
                            <div class="bg-white border px-3 py-3">
                                <div class="row align-items-center my-3">
                                    <div class="col-2">
                                        <i class="fas fa-print h3"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            Imprima o boleto e pague no banco
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center my-3">
                                    <div class="col-2">
                                        <i class="fas fa-barcode h3"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            ou pague pela internet utilizando o código de barras do boleto
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center my-3">
                                    <div class="col-2">
                                        <i class="fas fa-calendar h3"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            o prazo de validade do boleto é de 1 dias úteis
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center my-3">
                                    <div class="col-2">
                                        <i class="fas fa-cash-alt h3"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-0 h4 font-weight-bold">
                                            {{ cart.cartAmount | money }}
                                        </p>
                                    </div>
                                </div>
                                <button class="btn btn-success btn-block btn-lg botao_boleto" @click="toPay()" id="barcode">
                                    Finalizar pedido
                                </button>
                                <small class="text-danger" v-if="errors.finish">{{errors.finish}}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <button class="btn btn-primary text-white btn-block btn-lg" @click="setPayment('credit-card')">
                                <i class="fas fa-credit-card mr-3"></i>Cartão de Crédito
                            </button>
                        </div>
                    </div>
                    <div class="row my-3" v-if="userPayment.type == 'credit-card'">
                        <div class="col" id="focusCard">
                            <div class="bg-white border px-3 py-3">
                                <div class="form-group mt-3">
                                    <label for="" class="control-label">Nome no cartão:</label>
                                    <input type="text" class="form-control py-4" placeholder="Digite o nome impresso.." v-model="userPayment.name">
                                    <small class="text-danger" v-if="errors.cardName">{{ errors.cardName }}</small>
                                </div>
                                <div class="form-group mt-4">
                                    <label for="" class="control-label">Número no cartão:</label>
                                    <input type="text" class="form-control py-4" placeholder="Digite o numero impresso.." v-mask="'999999999999999[9]'" v-model="userPayment.card_number">
                                    <small class="text-danger" v-if="errors.card_number">{{ errors.card_number }}</small>
                                </div>
                                <div class="row my-0 py-0">
                                    <div class="form-group mt-4 col">
                                        <label for="" class="control-label">CPF do portador do cartão:</label>
                                        <input type="text" class="form-control py-4" placeholder="Digite o CPF do portador.." v-mask="'999.999.999-99'" v-model="userPayment.cardTaxNumber" @change="validateCardCPF(userPayment.cardTaxNumber)">
                                        <small class="text-danger" v-if="errors.cardTaxNumber">{{ errors.cardTaxNumber }}</small>
                                    </div>
                                    <div class="form-group mt-4 col">
                                        <label for="" class="control-label">Nome do portador do cartão:</label>
                                        <input type="text" class="form-control py-4" placeholder="Digite nome do portador.." v-model="userPayment.cardFullName">
                                        <small class="text-danger" v-if="errors.cardFullName">{{ errors.cardFullName }}</small>
                                    </div>
                                </div>
                                <div class="row my-0 py-0">
                                    <div class="form-group mt-4 col">
                                        <label for="" class="control-label">Nascimento do portador do cartão:</label>
                                        <input type="text" class="form-control py-4" placeholder="Digite o nascimento do portador.." v-mask="'99/99/9999'" v-model="userPayment.cardBirthdate">
                                        <small class="text-danger" v-if="errors.cardBirthdate">{{ errors.cardBirthdate }}</small>
                                    </div>
                                    <div class="form-group mt-4 col">
                                        <label for="" class="control-label">Telefone do portador do cartão:</label>
                                        <input type="text" class="form-control py-4" placeholder="Digite telefone do portador.." v-mask="'(99)99999-9999'" v-model="userPayment.cardPhoneNumber">
                                        <small class="text-danger" v-if="errors.cardPhoneNumber">{{ errors.cardPhoneNumber }}</small>
                                    </div>
                                </div>
                                <div class="row my-0 py-0">
                                    <div class="form-group mt-1 col-6">
                                        <label for="" class="control-label">Mês:</label>
                                        <input type="text" class="form-control py-4" placeholder="00" v-mask="'99'" v-model="userPayment.validate_month">
                                        <small class="text-danger" v-if="errors.validate_month">{{ errors.validate_month }}</small>
                                    </div>
                                    <div class="form-group mt-1 col-6">
                                        <label for="" class="control-label">Ano:</label>
                                        <input type="text" class="form-control py-4" placeholder="0000" v-mask="'9999'" v-model="userPayment.validate_year">
                                        <small class="text-danger" v-if="errors.validate_year">{{ errors.validate_year }}</small>
                                    </div>
                                </div>
                                <div class="row my-0 py-0 align-items-center">
                                    <div class="form-group mt-1 col-6">
                                        <label for="" class="control-label">CVC:</label>
                                        <input type="text" class="form-control py-4" placeholder="000" v-mask="'999[9]'" v-model="userPayment.cvc">
                                        <small class="text-danger" v-if="errors.cvc">{{ errors.cvc }}</small>
                                    </div>
                                    <p class="mb-3 mt-4 h4 font-weight-bold col-6">
                                        {{ cart.cartAmount | money }}
                                    </p>
                                </div>
                                <div class="row my-0 py-0 align-items-center">
                                    <div class="form-group mt-1 col-12">
                                        <label for="" class="control-label">Parcelamento:</label>
                                        <select v-model="userPayment.installment" class="form-control">
                                            <option :value="1">1 x {{ (cart.cartAmount / 1) | money }}</option>
                                            <option :value="2">2 x {{ (cart.cartAmount / 2) | money }}</option>
                                            <option :value="3">3 x {{ (cart.cartAmount / 3) | money }}</option>
                                            <option :value="4">4 x {{ (cart.cartAmount / 4) | money }}</option>
                                            <option :value="5">5 x {{ (cart.cartAmount / 5) | money }}</option>
                                            <option :value="6">6 x {{ (cart.cartAmount / 6) | money }}</option>
                                        </select>
                                    </div>
                                </div>

                                <button class="btn btn-success btn-block btn-lg botao_cartao" @click="toPay()">
                                    Finalizar pedido
                                </button>

                                <small class="text-danger" v-if="errors.finish">{{errors.finish}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-5">
                <div>
                    <h2 class="font-weight-bolder h5">Resumo da Compra</h2>
                    <div class="card py-5 px-4 my-4 bg-light">
                        <div class="row align-items-center">
                            <div class="col">
                                <p class="mb-0">Subtotal: ({{ cart.cartCount }} itens)</p>
                                <a href="/carrinho" class="text-info">
                                    <small>
                                        Ver produtos
                                    </small>
                                </a>
                            </div>
                            <div class="col text-right">
                                <p class="mb-0 font-weight-bold">{{ cart.cartSubtotal | money }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col">
                                <p class="mb-0">Frete:</p>
                            </div>
                            <div class="col text-right">
                                <p class="mb-0 font-weight-bold">{{ cart.cartShipping | money}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <p class="mb-0">Desconto:</p>
                            </div>
                            <div class="col text-right">
                                <p class="mb-0 font-weight-bold text-success">{{ cart.cartDiscount | money}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <p class="mb-0">Valor Total:</p>
                            </div>
                            <div class="col text-right">
                                <p class="mb-0 font-weight-bold">{{ cart.cartAmount | money }}</p>
                            </div>
                        </div>
                    </div>
                    <h2 class="font-weight-bolder h5">Cumpom de desconto</h2>
                    <div class="card pt-4 pb-3 px-4 my-4">
                        <div v-if="!cart.couponDescription && !cart.couponBenefit" class="row align-items-center">
                            <div class="col-8">
                                <input type="text" class="form-control py-4" placeholder="Digite seu Cupom.." v-model="userCoupon.title">
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary text-white btn-sn" id="btnCoupon" @click="getCoupon()">
                                    <i></i>Adicionar a compra
                                </button>
                            </div>
                            <small class="text-danger ml-3 mt-3" v-if="errors.coupon">{{ errors.coupon }}</small>
                        </div>

                        <div class="row align-items-center" v-else>
                            <div class="col-12 col-md-12">
                                <div class="form-group mt-3">
                                    <label class="control-label">Cupom utilizado: <b>{{cart.couponDescription}}: <span class="text-success">{{cart.couponBenefit}}</span></b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center px-5">
                    <p class="h6 text-success">
                        <i class="fas fa-lock mr-2"></i> Compra Segura
                    </p>
                </div>
            </div>
        </div>



        <div class="row" v-if="success != 0 && error == 0">
            <div class="col-md-8">
                <h2 class="font-weight-bolder h5">Pedido realizado</h2>
                <div class="card py-5 px-4 my-4">
                    <h2 class="font-weight-bolder h4">O seu pedido foi gerado com sucesso.</h2>
                    <p>Você pode acompanhar os detalhes do pagamento pela sua conta: <a href="/minha-conta" class="text-secondary">Minha Conta</a>.</p>
                    <p class="my-4 h5 font-weight-bolder" v-if="success.barcode_number">{{ success.barcode_number }}</p>
                    <a class="btn btn-success btn-lg w-50" :href="success.barcode_url" v-if="success.barcode_url" target="_blank">
                        <i class="fas fa-print mr-3"></i>Imprimir Boleto
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <h2 class="font-weight-bolder h5">Resumo da Compra</h2>
                <div class="card py-5 px-4 my-4 bg-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="mb-0">Subtotal: ({{ cart.cartCount }} itens)</p>
                            <a href="" class="text-info">
                                <small>
                                    Ver produtos
                                </small>
                            </a>
                        </div>
                        <div class="col text-right">
                            <p class="mb-0 font-weight-bold">{{ cart.cartSubtotal | money }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="mb-0">Frete:</p>
                        </div>
                        <div class="col text-right">
                            <p class="mb-0 font-weight-bold">{{ cart.cartShipping | money}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <p class="mb-0">Desconto:</p>
                        </div>
                        <div class="col text-right">
                            <p class="mb-0 font-weight-bold text-success">{{ cart.cartDiscount | money}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <p class="mb-0">Valor Total:</p>
                        </div>
                        <div class="col text-right">
                            <p class="mb-0 font-weight-bold">{{ cart.cartAmount | money }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="success == 0 && error != 0">
            <div class="col-md-8">
                <h2 class="font-weight-bolder h5">Pedido falhou</h2>
                <div class="card py-5 px-4 my-4">
                    <h2 class="font-weight-bolder h4">O seu pedido não pode ser gerado.</h2>
                    <p>O seu pedido não pode ser concluido. Pedimos que tente novamente em instantes e caso o erro persista, entre com nossa equipe de <a href="" class="text-secondary">suporte</a>.</p>
                </div>
            </div>
        </div>

        <div class="row invisible-div">
            <div class="col"></div>
        </div>

    </div>

</template>

<script>

    import moment from 'moment';

    import { MoipCreditCard, MoipValidator } from 'moip-sdk-js';
    import jsencrypt from 'jsencrypt';
    import { validateCPF }  from '../../helpers/validations';

    export default {

        props: ["user", "moip_pub_key"],

        data() {
            return {

                cart: {
                    shippings: [],
                    zipcodeTo: null
                },

                userDetails: {

                },
                userAddress: {
                    street: null,
                    district: null,
                    city: null,
                    state: null,
                },
                userPayment: {
                    type : null,
                    installment : 1,
                    cardTaxNumber: null,
                    cardFullName: null,
                    cardBirthdate: null,
                    cardPhoneNumber: null,
                },
                userCoupon: {
                    title: ''
                },
                payment: {},
                success: 0,
                error: 0,
                errors: {
                    zipcodeTo: null,
                    name: null,
                    tax_document_number: null,
                    birthdate: null,
                    cellphone: null,
                    zipcode: null,
                    street: null,
                    street_number: null,
                    district: null,
                    city: null,
                    state: null,
                    cardName: null,
                    card_number: null,
                    validate_month: null,
                    validate_year: null,
                    cvc: null,
                    cardTaxNumber: null,
                    cardFullName: null,
                    cardBirthdate: null,
                    cardPhoneNumber: null,
                    coupon: null,
                    finish: null,
                },
                loading: false,
                calculating: false,

            }
        },

        methods:{

            getCoupon: function()
            {
                $("#btnCoupon").attr('disabled', true);

                axios
                    .post('/allowed-coupons', {
                            cart: this.cart,
                            coupon: this.userCoupon,
                        })
                    .then(response => {

                        $("#btnCoupon").attr('disabled', false);

                        if(response.status != 203){

                            let coupon = response.data.coupon;

                            //VERIFICAR SE O CUPOM DE DESCONTO EH EM CIMA DO FRETE OU EM CIMA DO DINHEIRO
                            if(coupon.target == 0){
                                //EM CIMA DO PRECO

                                //VERIFICAR SE O TIPO DE REDUCAO EH PORCENTAGEM OU DINHEIRO
                                if(coupon.type == 0){
                                    //EM CIMA DA PORCENTAGEM

                                    //VERIFICAR SE A REDUCAO EH EM CIMA DO PRECO FINAL OU DO PRODUTO

                                    let percentage = (this.cart.cartAmount * parseFloat(coupon.reduction_amount)) / 100;

                                    this.cart.cartDiscount += percentage;
                                    this.cart.couponDescription = coupon.title;
                                    this.cart.couponBenefit = "- " + coupon.reduction_amount + "% do valor total";
                                    this.updateCart();

                                }else{
                                    //EM CIMA DO DINHEIRO

                                    this.cart.cartDiscount += parseFloat(coupon.reduction_amount);
                                    this.cart.couponDescription = coupon.title;
                                    this.cart.couponBenefit = "- R$" + coupon.reduction_amount + " do valor total";
                                    this.updateCart();
                                }
                            }else{  //EM CIMA DO FRETE

                                if(this.cart.zipcodeTo == null){
                                    this.errors.coupon = 'Por favor informe o cep e selecione um frete para adicionar cupons de frete';
                                    return false;
                                }

                                //VERIFICAR SE O TIPO DE REDUCAO EH PORCENTAGEM OU DINHEIRO
                                if(coupon.type == 0){

                                    let percentage = (this.cart.cartShipping * parseFloat(coupon.reduction_amount)) / 100;

                                    this.cart.cartDiscount += percentage;
                                    this.cart.couponDescription = coupon.title;
                                    this.cart.couponBenefit = "- " + coupon.reduction_amount + "% do valor do frete";
                                    this.updateCart();

                                }else{

                                    //EM CIMA DO DINHEIRO
                                    this.cart.cartDiscount += parseFloat(coupon.reduction_amount);
                                    this.cart.couponDescription = coupon.title;
                                    this.cart.couponBenefit = "- R$" + coupon.reduction_amount + " do valor do frete";
                                    this.updateCart();

                                }

                            }
                        } else {
                            this.errors.coupon = response.data.mensagem;
                        }
                    })
                    .catch(err => {

                    })
            },

            setPayment: async function(type)
            {

                this.userPayment = {
                    type: type,
                    installment : 1,
                }

                window.scrollTo({
                    top: 99999,
                    left: 0,
                    behavior: "smooth"
                });
            },

            getAddress(cep){
                self = this;

                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function(address){

                    self.userAddress.street = address.logradouro;
                    self.userAddress.district = address.bairro;
                    self.userAddress.city = address.localidade;
                    self.userAddress.state = address.uf;

                });
            },

            toPay: function()
            {
                if(this.validations()){
                    if(this.userPayment.type == 'credit-card'){

                        $(".botao_cartao").prop("disabled", true);
                        $(".botao_cartao").text("Realizando pagamento...");

                        MoipCreditCard
                            .setEncrypter(jsencrypt, 'ionic')
                            .setPubKey(this.moip_pub_key)
                            .setCreditCard({
                                number  : this.userPayment.card_number,
                                cvc     : this.userPayment.cvc,
                                expirationMonth: parseInt(this.userPayment.validate_month),
                                expirationYear : parseInt(this.userPayment.validate_year),
                            })
                            .hash()
                            .then(hash => {
                                axios
                                    .post('/payments', {
                                        cart: this.cart,
                                        details: this.userDetails,
                                        address: this.userAddress,
                                        payment: {
                                            type: this.userPayment.type,
                                            installment: this.userPayment.installment,
                                            cardTaxNumber: this.userPayment.cardTaxNumber,
                                            cardFullName: this.userPayment.cardFullName,
                                            cardBirthdate: this.userPayment.cardBirthdate,
                                            cardPhoneNumber: this.userPayment.cardPhoneNumber,
                                            hash: hash,
                                        },
                                    })
                                    .then(response => {
                                        this.success = response.data.payment;
                                    })
                                    .catch(err => {
                                        $(".botao_cartao").prop("disabled", false);
                                        $(".botao_cartao").text("Realizar Pagamento");
                                        this.error = 1;
                                    });

                            }).catch(err =>{
                                console.log(err);
                            });


                    } else if(this.userPayment.type == 'barcode'){


                        $(".botao_boleto").prop("disabled", true);
                        $(".botao_boleto").text("Gerando boleto...");

                        axios
                            .post('/payments', {
                                cart: this.cart,
                                details: this.userDetails,
                                address: this.userAddress,
                                payment: {
                                    type: this.userPayment.type,
                                },
                            })
                            .then(response => {
                                this.success = response.data.payment;
                            })
                            .catch(err => {

                                this.error = 1;

                                $(".botao_boleto").prop("disabled", false);
                                $(".botao_boleto").text("Gerar Boleto");

                            });

                    }

                    this.$store.commit('cleanCart',this.now)

                }
            },

            selectShipping: function(){

                this.cart.cartStatus = true;

                this.cart.cartShipping = this.cart.shipping.price

                this.updateCart();

            },

            updateCart: function() {
                this.loading = true;

                // this.cart.cartDiscount = discount;
                this.cart.cartAmount = this.cart.cartSubtotal + parseFloat(this.cart.cartShipping) - parseFloat(this.cart.cartDiscount);

                axios
                    .patch('/carrinho/details', {cart: this.cart})
                    .then(response => {

                        this.cart = response.data

                        this.loading = false;

                    });

            },

            validateInfoCPF: function(cpf){
                let validation = validateCPF(cpf);

                if(validation == false) this.errors.tax_document_number = 'Por favor informe um CPF valido';
                else this.errors.tax_document_number = false;
            },

            validateCardCPF: function(cpf){
                let validation = validateCPF(cpf);

                if(validation == false) this.errors.cardTaxNumber = 'Por favor informe um CPF valido';
                else this.errors.cardTaxNumber = false;
            },

            validations: function() {

                if(!this.cart.zipcodeTo){
                    this.errors.zipcodeTo = 'Por favor informe um CEP para o calculo do frete.';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';

                    return false;

                } else {

                    this.errors.finish = null;
                    this.errors.zipcodeTo = null
                }

                if(!this.user.name){
                    this.errors.name = 'Por favor informe seu nome';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';

                    return false;
                } else {

                    this.errors.finish = null;
                    this.errors.name = null
                }

                if(!this.userDetails.tax_document_number){
                    this.errors.tax_document_number = 'Por favor informe seu CPF';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';
                    return false;
                } else {

                    this.errors.finish = null;
                    this.errors.tax_document_number = null
                }

                if(!this.userDetails.birthdate || moment(this.userDetails.birthdate, "DD/MM/YYYY").format("DD/MM/YYYY") == "Invalid date"){

                    this.errors.birthdate = 'Por favor informe sua data de nascimento';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';

                    return false;

                } else {

                    this.errors.finish = null;
                    this.errors.birthdate = null

                }

                if(!this.userDetails.cellphone){

                    this.errors.cellphone = 'Por favor informe seu celular';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';

                    return false;

                } else {

                    this.errors.finish = null;
                    this.errors.cellphone = null

                }

                if(!this.userAddress.zipcode){

                    this.errors.zipcode = 'Por favor informe seu CEP';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';

                    return false;

                } else {

                    this.errors.finish = null;
                    this.errors.zipcode = null

                }

                if(!this.userAddress.street){

                    this.errors.street = 'Por favor informe sua rua';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';

                    return false;

                } else {

                    this.errors.finish = null;
                    this.errors.street = null

                }

                if(!this.userAddress.street_number){
                    this.errors.street_number = 'Por favor informe o número';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';
                    return false;
                } else {

                    this.errors.finish = null;
                    this.errors.street_number = null
                }

                if(!this.userAddress.district){
                    this.errors.district = 'Por favor informe seu bairro';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';
                    return false;
                } else {

                    this.errors.finish = null;
                    this.errors.district = null
                }

                if(!this.userAddress.city){
                    this.errors.city = 'Por favor informe sua cidade';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';
                    return false;
                } else {

                    this.errors.finish = null;
                    this.errors.city = null
                }

                if(!this.userAddress.state){
                    this.errors.state = 'Por favor informe seu estado';
                    this.errors.finish = 'Erros foram encontrados. por favor verifique os campos';
                    return false;
                } else {

                    this.errors.finish = null;
                    this.errors.state = null
                }


                if(this.userPayment.type == 'credit-card'){

                    if(!this.userPayment.name){
                        this.errors.cardName = 'Por favor informe o nome do responsável pelo cartão';
                        return false;
                    } else {this.errors.cardName = null}

                    if(!this.userPayment.card_number || !MoipValidator.isValidNumber(this.userPayment.card_number)){
                        this.errors.card_number = 'Por favor informe o número do cartão ou verifique o mesmo';
                        return false;
                    } else {this.errors.card_number = null}

                    if(!this.userPayment.cardTaxNumber){

                        this.errors.cardTaxNumber = 'Por favor informe o CPF do portador do cartão'
                        return false;

                    } else {this.errors.cardTaxNumber = null}

                    if(!this.userPayment.cardFullName){

                        this.errors.cardFullName = 'Por favor informe o nome do portador do cartão'
                        return false;

                    } else {this.errors.cardFullName = null}

                    if(!this.userPayment.cardBirthdate){

                        this.errors.cardBirthdate = 'Por favor informe o nascimento do portador do cartão'
                        return false;

                    } else {this.errors.cardBirthdate = null}

                    if(!this.userPayment.cardPhoneNumber){

                        this.errors.cardPhoneNumber = 'Por favor informe o telefone do portador do cartão'
                        return false;

                    } else {this.errors.cardPhoneNumber = null}

                    if(!this.userPayment.validate_month || this.userPayment.validate_month < 1 || this.userPayment.validate_month > 12 ){
                        this.errors.validate_month = 'Por favor informe uma data de validade válida';
                        return false;
                    } else {this.errors.validate_month = null}

                    if(!this.userPayment.validate_year || this.userPayment.validate_year < moment().format("YYYY") ){
                        this.errors.validate_year = 'Por favor informe o ano de vencimento';
                        return false;
                    } else {this.errors.validate_year = null}

                    if(!this.userPayment.cvc || MoipValidator.isSecurityCodeValid(this.userPayment.card_number, this.userPayment.cvc)){
                        this.errors.cvc = 'Por favor informe o código de segurança ou verifique o mesmo';
                        return false;
                    } else {this.errors.cvc = null}

                }

                return true;

            },



            calculateDelivery: function(){

                this.calculating = true;

                var productsToCalculate = [];

                this.cart.products.forEach(product => {
                    productsToCalculate.push({
                        id: product.id,
                        product_id: product.product_id,
                        quantity: product.quantity,
                        weight: product.weight,
                        product: {
                            depth: product.product.depth,
                            height: product.product.heigth,
                            id: product.product.id,
                            images: product.product.images,
                            short_description: product.product.short_description,
                            title: product.product.title,
                            width: product.product.width
                        }
                    })
                });

                axios
                    .post('/calculo-frete',{ products: productsToCalculate, zipcode_to: this.cart.zipcodeTo })
                    .then(response => {
                        this.cart.shippings = response.data.shippings;

                        this.cart.shipping = this.cart.shippings[0];

                        this.calculating = false;
                        this.selectShipping();
                    })
                    .catch(err => {

                        this.calculating = false;

                    })

            },

            getCep: function(cep){

                self = this;

                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function(address){

                    if(address.erro){
                        $("#inputLogradouro").focus();
                        return;
                    }

                    self.userAddress.zipcode = cep;
                    self.userAddress.street = address.logradouro;
                    self.userAddress.district = address.bairro;
                    self.userAddress.city = address.localidade;
                    self.userAddress.state = address.uf;

                });
            }

        },

        mounted(){

            axios
                .get('/carrinho/details')
                .then(response => {

                    this.cart = response.data


                    if(this.cart.products.length == 0){
                        window.location = "/"
                    }

                    if(this.cart.zipcodeTo){

                        this.userAddress.zipcode = this.cart.zipcodeTo;
                        this.getAddress(this.userAddress.zipcode);

                    }

                    if(this.cart.couponDescription){
                        this.userCoupon.title = this.cart.couponDescription;
                        this.getCoupon();
                    }

                    if(this.cart.shippings.length == 0 || this.cart.shippings == undefined){

                        this.cart.shippings = [];

                    } else {

                        this.calculateDelivery();

                    }

                })
                .catch(err => {
                    console.log(err)
                })

            if(this.user.details){
                this.userDetails = this.user.details;
                this.userDetails.birthdate = moment(this.userDetails.birthdate, "YYYY-MM-DD").format("DD/MM/YYYY")
                this.userDetails.cellphone = '(' + this.userDetails.phone_area_code + ')' + this.userDetails.phone_number;
            }

        }

    }

</script>

<style>
    .invisible-div{
        height: 400px;
    }
</style>
