<template>
    <div>
        <div v-if="variants.length > 0">
            <div class="row mt-3">
                <div class="col col-md mb-3" v-for="option in options" :key="option.id">
                    <label for="">{{ option.title }}:</label>
                    <select @change="setOptions()" class="form-control input-option" v-model="option.selected">
                        <option :value="null">- Selecione -</option>
                        <option v-for="value in option.values" :key="value.option_value_id" :value="value">{{ value.title }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-if="buyProduct.variant">

            <button class="btn btn-lg btn-primary text-uppercase font-weight-bold my-3" @click="buy()">
                <i class="fas fa-plus mr-2"></i> Comprar Produto
            </button>

            <div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="buyModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-10 text-right">
                                    <p class="h4 font-weight-bold modal-title">Produto adicionado com sucesso ao carrinho.</p>
                                </div>
                                <div class="col-2 mt-2">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-7">
                                            <img class="d-block w-100"
                                                 :src="buyProduct.variant.images[0].url"
                                                 :alt="product.short_description"
                                                 :title="product.title">
                                        </div>
                                        <div class="col-5 text-left">
                                            <p class="h5 mt-1">{{ product.title }}</p>
                                            <p class="h5">{{ buyProduct.variant.promote_price ? buyProduct.variant.promote_price : buyProduct.variant.price  | money }}</p>
                                            <p class="h6" v-for="value in buyProduct.optionsValues" :key="value.id">
                                                {{ value.option_title }}: {{ value.value_title }}
                                            </p>
                                            <p class="h6">
                                                Quantidade: 1
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-left">
                                    <p class="h3 font-weight-bold">Seu Carrinho</p>
                                    <p class="h6">{{ cart.cartCount > 1 ? cart.cartCount + ' itens' : cart.cartCount + ' item' }} </p>
                                    <div class="row ">
                                        <div class="col">
                                            <p class="h6">Somatoria dos produtos:</p>
                                        </div>
                                        <div class="col">
                                            <p class="h6"> {{ cart.amount | money }} </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col">
                                            <a :href="'/carrinho'" class="btn btn-primary btn-block btn-md">
                                                <i class="fas fa-shopping-cart mr-2"></i>Meu Carrinho
                                            </a>
                                            <a :href="currentPage" class="btn btn-light btn-block btn-md">
                                                <i class="fas fa-home mr-2"></i>Continuar Comprando
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div v-if="problemStock">
            <div class="row my-3">
                <div class="col">
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-0 pb-0"><small>Oops! Já vendemos todas as unidades disponiveis em nosso estoque.</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    export default {

        props: ['slug', 'variants', 'options', 'product'],
        computed: {
            currentPage: function() {
                return location.href;
            }
        },

        data() {
            return {
                problemStock: false,
                buyProduct: {
                    variant: false,
                    optionsValues: []
                },
                cart:{}
            }
        },
        methods: {

            setOptions: function()
            {

                // separa as opcoes selecionadas
                this.buyProduct.optionsValues = [];

                this.options.forEach(option => {
                    if(option.selected) {
                        this.buyProduct.optionsValues.push({
                            option_id: option.id,
                            option_title: option.title,
                            value_id: option.selected.pivot.option_value_id,
                            value_title: option.selected.title,
                        })
                    }
                });

                // se a quantidade selecionada for igual ao total de opcoes
                if(this.options.length == this.buyProduct.optionsValues.length){

                    let founds = [];

                    // passa em todas as variacoes
                    this.variants.forEach(variant => {

                        // passa em todas as opcoes da variacao
                        variant.options.forEach(option => {

                            // passa em todas as opcoes selecionadas
                            this.buyProduct.optionsValues.forEach(optionValue =>{

                                // se a opcao da variacao estiver presente nas opcoes selecionadas
                                if(optionValue.option_id == option.option && optionValue.value_id == option.value) {
                                    // adiciona o id da variacao em encontrados
                                    if(variant.quantity > 0){
                                        founds.push(variant.id)
                                    }
                                }

                            })

                        })
                    });

                    // procura o id que mais se repete no array de encontrados
                    var uniqs = {};

                    for(var i = 0; i < founds.length; i++) {
                        uniqs[founds[i]] = (uniqs[founds[i]] || 0) + 1;
                    }

                    var max = { val: founds[0], count: 1 };
                    for(var u in uniqs) {
                        if(max.count < uniqs[u]) { max = { val: u, count: uniqs[u] }; }
                    }

                    if(max.count == this.options.length){

                        // define a variacao selecionada
                        this.buyProduct.variant = this.variants.find(variant => {

                            return variant.id == max.val
                        });

                        // habilita o botao de compra
                        if(this.buyProduct.variant){
                            this.problemStock = false;

                            let price = this.buyProduct.variant.promote_price != '0.00' ? this.buyProduct.variant.promote_price : this.buyProduct.variant.price;

                            $("#productPrice").text(this.$options.filters.money(price))
                            $("#productInstallmentPrice").text(this.$options.filters.money(price / this.product.installments))

                        }else{

                            this.buyProduct.variant = false;
                            this.problemStock = true;

                        }

                    } else {
                        this.buyProduct.variant = false;
                        this.problemStock = true;
                    }

                } else {

                    this.buyProduct.variant = false;

                }

            },

            buy: async function(){

                try{

                    const {data} = await axios.post('/carrinho', {
                        item: this.buyProduct.variant.id
                    });

                    this.cart = data.cart;

                    $("#buyModal").modal('show');

                }catch(e){

                }



            }

        },
        mounted() {


        }

    }
</script>
