<template>

    <div class="container mt-5 pt-md-0 py-5">
        <div class="row">
            <div class="col-md-8">
                <h3 class="font-weight-bolder text-uppercase">Seu Carrinho</h3>
                <h1 class="font-weight-light h5">TOTAL ({{ cart.cartCount > 0 ? cart.cartCount + ' produtos' : cart.cartCount + ' produto' }}) | <b>{{ cart.cartAmount | money }}</b></h1>
                <div class="card py-3 px-3 my-4" v-for="(product, i) in cart.products" :key="product.id">
                    <div class="row">
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col-3 col-md-4">
                                    <img :src="product.product.images[0] ? product.product.images[0].url : '/images/no_product.png'" class="w-100" alt="">
                                </div>
                                <div class="col">
                                    <p class="font-weight-bold mb-0">{{ product.product.title }}</p>
                                    <p class=" d-none d-md-block mb-2">{{ product.product.short_description }}</p>
                                    <p class=" d-none d-md-block mb-0" v-for="value in product.values" :key="value.id">
                                        <small class="text-dark font-weight-bold">{{ value.title }}, </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 text-right">
                            <button data-toggle="modal" data-target="#confirmModal" class="btn btn-link btn-sm" @click="confirmRemoveFromCart(i, product)">
                                <i class="fas fa-trash "></i>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class=" col col-md-1 text-center px-0">
                            <button :id="'btnRemove' + i" class="btn btn-link" @click="subQuantity(product, i)">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                        </div>
                        <div class="col col-md-2 text-left px-2">
                            <input type="number" class="form-control" placeholder="1" v-model="product.quantity" @blur="updateCart()" readonly>
                                <small class="text-danger" v-if="product.stockError">{{ product.stockError }}</small>
                        </div>
                        <div class="col col-md-1 text-center px-0">
                            <button :id='"btnAdd" + i'  class="btn btn-link" @click="addQuantity(product, i)">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="col text-right">
                            <p class="mb-0 font-weight-bold h6 pl-0" v-if="product.stocks[0].promote_price == '0.00'">
                                {{ product.stocks[0].price * product.quantity | money }}
                            </p>
                            <p class="mb-0 font-weight-bold h6 pl-0" v-else>
                                {{ product.stocks[0].promote_price * product.quantity | money }}
                            </p>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary btn-lg mb-1 d-none d-md-block" id="btnCheckout" v-if="user" href="/checkout">
                    Continuar
                </a>
                <button class="btn btn-primary btn-lg mb-1 d-none d-md-block" v-if="!user" id="btnCheckout" data-target="#accountModal" data-toggle="modal">
                    Continuar
                </button>
            </div>
            <div class="col-md-4">
                <a class="btn btn-primary btn-block btn-lg mb-1" v-if="user" href="/checkout" id="btnCheckout">
                    Continuar
                </a>
                <button class="btn btn-primary btn-block btn-lg mb-1" v-if="!user" data-target="#accountModal" id="btnCheckout" data-toggle="modal">
                    Continuar
                </button>
                <div class="card py-5 px-4 my-4 mt-4 bg-light">
                    <h1 class="font-weight-bolder h5 text-uppercase">Resumo do pedido</h1>
                    <div class="row">
                        <div class="col">
                            <p class="mb-0">Subtotal: ({{ cart.cartCount }} itens)</p>
                        </div>
                        <div class="col text-right">
                            <p class="mb-0 font-weight-bold" v-if="cart.cartSubtotal > 0">{{ cart.cartSubtotal | money }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="mb-0">Frete:</p>
                            <small>O frete é calculado na tela de pagamento</small>
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

        <!-- The Modal -->
        <div class="modal fade" id="confirmModal">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Atenção</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        Você realmente deseja remover esse {{ excluir.mensagem }} do carrinho?
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                        <button type="button" class="btn btn-success" @click="removeFromCart()" data-dismiss="modal">Sim, remover</button>
                    </div>

                </div>
            </div>
        </div>

    </div>

</template>

<script>

    export default {

        props: ["user"],

        data() {
            return {

                cart: {
                    products: [],
                    shippings: [],
                    zipcodeTo: null,
                    cartCount: 0,
                    cartShipping: 0,
                    cartSubtotal: 0,
                    cartAmount: 0,
                    cartDiscount: 0,
                    shipping:{},
                    cartStatus: false,
                },
                loading: false,
                excluir: {
                    mensagem: null,
                },
                stocks: [],
                userAddress: {
                    district: null,
                    city: null,
                },
            }
        },

        methods:{

            getDetails: async function(){
                try{

                    const {data} = await axios.get('/carrinho/details');

                    this.cart.products = data.products
                    this.cart.zipcodeTo = data.zipcodeTo ? data.zipcodeTo : null
                    this.cart.shippings = data.shippings ? data.shippings : []
                    this.cart.cartCount = data.cartCount ? data.cartCount : 0
                    this.cart.cartShipping = data.cartShipping ? data.cartShipping : 0
                    this.cart.cartSubtotal = data.cartSubtotal ? data.cartSubtotal : 0
                    this.cart.cartAmount = data.amount ? data.amount : 0
                    this.cart.cartDiscount = data.cartDiscount ? data.cartDiscount : 0
                    this.cart.couponDescription = data.couponDescription ? data.couponDescription : null
                    this.cart.shipping = data.shipping

                    if(this.cart.shipping)
                    {
                        this.cart.cartStatus = true;
                    }

                    if(this.cart.products.length > 0 )
                    {
                        this.getCep();
                    }

                    this.cart.products.forEach(product =>{

                        product.stockError = null;

                    });

                    this.updateCart();

                }catch(e){
                    console.log(e)
                }

            },

            calculateDelivery: function(){

                this.loading = true;

                var productsToCalculate = [];

                this.cart.products.forEach(product => {
                    productsToCalculate.push({
                        id: product.id,
                        product_id: product.product_id,
                        quantity: product.quantity,
                        weight: product.weight,
                        product: {
                            depth: product.product.depth,
                            height: product.product.height,
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
                        this.cart.shipping = response.data.shippings.shippings[0];
                        this.loading = false;
                        this.selectShipping();
                    })
                    .catch(err => {
                        this.loading = false;
                    })

            },

            confirmRemoveFromCart: function(i, product_delete){
                this.excluir.index = i;
                this.excluir.product_delete = product_delete;

                if(this.excluir.product_delete.kit_id){
                    this.excluir.mensagem = "kit";
                }else{
                    this.excluir.mensagem = "produto";
                }
            },

            removeFromCart: function(){
                self = this;

                if(this.excluir.product_delete.kit_id){
                    var index = this.cart.products.length;

                    while(index-- > 0){
                        if(this.cart.products[index].kit_id == this.excluir.product_delete.kit_id){
                            self.cart.products.splice(index,1)
                        }
                    }
                }else{
                    self.cart.products.splice(this.excluir.index,1)
                }

                if(this.stocks.length == 0){
                    this.calculateDelivery();
                }
                this.updateCart();
            },

            subQuantity: function(product, index){

                $("#btnRemove" + index).attr('disabled', true);

                product.quantity--;

                if(product.quantity <= 0){
                    this.cart.products.splice(index, 1);
                }

                if(this.stocks.length == 0){
                    this.calculateDelivery();
                }

                if(product.stocks[0].quantity <= product.quantity){

                    product.stockError = null;

                    $("#btnCheckout").attr('disabled', false);
                }
                this.updateCart(index);

            },

            addQuantity: function(product, i){

                $("#btnAdd" + i).attr('disabled', true);

                product.quantity++;

                if(this.stocks.length == 0){
                    this.calculateDelivery();
                }

                if(product.stocks[0].quantity < product.quantity){

                    product.stockError = 'A quantidade desejada não está disponivel';
                    $("#btnCheckout").attr('disabled', true);

                } else {
                    product.stockError = null;

                    $("#btnCheckout").attr('disabled', false);

                    this.updateCart(i);
                }


            },

            updateCart: function(index) {
                this.loading = true;

                let count = 0;
                let subtotal = 0;
                let discount = 0;

                this.cart.products.forEach(product => {
                    count = count + parseInt(product.quantity)

                    if(product.stocks[0].promote_price == '0.00'){
                        subtotal = subtotal + (parseInt(product.quantity) * parseFloat(product.stocks[0].price))
                    }else{
                        subtotal = subtotal + (parseInt(product.quantity) * parseFloat(product.stocks[0].promote_price))
                    }

                    discount = discount + (parseInt(product.quantity) * parseFloat(product.stocks[0].price)) * parseInt(product.discount) / 100;
                });

                if(this.cart.products.length == 0){
                    this.cart.cartShipping = 0;
                }

                this.cart.cartCount = count;
                this.cart.cartSubtotal = subtotal;
                this.cart.cartDiscount = discount;
                this.cart.cartAmount = this.cart.cartSubtotal + parseFloat(this.cart.cartShipping) - parseFloat(this.cart.cartDiscount);

                axios
                    .patch('/carrinho/details', {cart: this.cart})
                    .then(response => {

                        this.cart = response.data;
                        $("#btnAdd" + index).attr('disabled', false);
                        $("#btnRemove" + index).attr('disabled', false);

                    })

                this.loading = false;

            },

            selectShipping: function(){

                this.cart.cartStatus = true;
                this.cart.cartShipping = this.cart.shipping.price
                this.updateCart();

            },

            getStocks: function() {

                Http
                    .get('/stocks', { params: {
                        // district : this.userAddress.district,
                        city : this.userAddress.city,
                    }})
                    .then(response => {
                        this.cart.cartShipping = response.data.deliveries[0].tax;
                        this.cart.shippings.shippings = [];
                        this.cart.cartStatus = true;
                        this.updateCart();
                    })
                    .catch(err => {
                        this.stocks = [];
                        this.userAddress = {
                            district: null,
                            city: null,
                        }
                        this.calculateDelivery();
                    })
                },

            getCep: function() {

                this.loading = true

                if(this.cart.zipcodeTo){

                    axios
                        .get("https://viacep.com.br/ws/" + this.cart.zipcodeTo + "/json/?callback=?")
                        .then(response => {

                            let data =  JSON.parse(response.data.slice(2,-2));
                            if(data.erro){
                                $("#zipcodeInput").removeClass('is-valid');
                                $("#zipcodeInput").addClass('is-invalid');
                                this.cart.cartStatus = false;
                                this.loading = false;
                            } else {
                                this.userAddress.zipcode = data.cep;
                                this.userAddress.street = data.logradouro;
                                this.userAddress.district = data.bairro;
                                this.userAddress.city = data.localidade;
                                this.userAddress.state = data.uf;
                                this.getStocks();
                                this.cart.cartStatus = true;
                                $("#zipcodeInput").removeClass('is-invalid');
                                $("#zipcodeInput").addClass('is-valid');
                            }
                        })
                }

                this.loading = false;

            }

        },

        mounted(){
            this.getDetails();

        }

    }

</script>
