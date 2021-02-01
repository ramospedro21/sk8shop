<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="container-fluid mt-5">
            <form @submit.prevent="product.id ? update() : store()">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Sobre</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">Nome: *</label>
                                            <input type="text" class="form-control" v-model="product.title">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">Descrição Curta: *</label>
                                            <input type="text" class="form-control" v-model="product.short_description">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">Descrição: *</label>
                                            <textarea cols="30" rows="10" v-model="product.description" class="form-control">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow mt-1">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Variações</h4>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" class="btn btn-success btn-sm btn-round" @click="openVariantModal()">
                                            <i class="fas fa-plus mr-2"></i>Novo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>SKU</th>
                                            <th>VARIAÇÕES</th>
                                            <th>QTD. DISPONIVEL</th>
                                            <th>PREÇO</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(variant, i) in product.variants" :key="'variant' + variant.id">
                                            <td>{{ variant.sku }}</td>
                                            <td>
                                                <span v-for="option in variant.values" :key="option.id">
                                                    {{ option.title }}; 
                                                </span>
                                            </td>
                                            <td>
                                                <span v-if="variant.stocks.length > 0">
                                                    {{ variant.stocks[0].quantity }}
                                                </span>
                                                <span v-else>
                                                    0
                                                </span> 
                                            </td>
                                            <td>
                                                <span v-if="variant.stocks.length > 0">
                                                    {{ variant.stocks[0].price }}
                                                </span>
                                                <span v-else>
                                                    0
                                                </span> 
                                            </td>
                                            <td>
                                                <i class="fas fa-edit pointer" @click="openEditVariant(variant, i)"></i>
                                                <i class="fas fa-trash pointer" @click="openDeleteVariant(variant, i)"></i>
                                            </td>
                                        </tr>   
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Detalhes</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Parcelas: *</label>
                                            <input type="number" min="1" max="12" class="form-control"
                                                   v-model="product.installments">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Exibir no site: *</label>
                                            <select class="form-control" v-model="product.enabled">
                                                <option :value="null">-Selecione-</option>
                                                <option :value="0">Sim</option>
                                                <option :value="1">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Retirar na loja: *</label>
                                            <select class="form-control" v-model="product.self_delivery">
                                                <option :value="null">-Selecione-</option>
                                                <option :value="0">Sim</option>
                                                <option :value="1">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Largura: *</label>
                                            <input type="text" class="form-control" v-model="product.width">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Altura: *</label>
                                            <input type="text" class="form-control" v-model="product.heigth">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Profundidade: *</label>
                                            <input type="text" class="form-control" v-model="product.depth">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h4 class="mb-0">Categorias</h4>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-success btn-sm btn-round" @click="openCategoriesModal()">
                                            <i class="fas fa-plus mr-2"></i>Novo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr v-for="(category, i) in product.categories" :key="'category' + category.id">
                                            <td>{{ !category.title ? category.category.title : category.title }}</td>
                                            <td>
                                                <i class="fas fa-trash pointer" @click="product.categories.splice(i, 1)"></i>
                                            </td>
                                        </tr>   
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h4 class="mb-0">Cupons de desconto</h4>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-success btn-sm btn-round" @click="openCouponsModal()">
                                            <i class="fas fa-plus mr-2"></i>Novo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <!-- <tr v-for="(category, i) in product.categories" :key="'category' + category.id">
                                            <td>{{ !category.title ? category.category.title : category.title }}</td>
                                            <td>
                                                <i class="fas fa-trash pointer" @click="product.categories.splice(i, 1)"></i>
                                            </td>
                                        </tr>    -->
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="variantModal" tabindex="-1" role="dialog" aria-labelledby="variantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-image modal-lg">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title" id="variantModalLabel">{{ variant.id ? 'Editar' : 'Adicionar' }} Variação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="variant.id ? editVariant(variant.index) : addVariant()">
                    <div class="modal-body-image">
                        <div class="row">
                            <div class="col">
                                <div class="card-shadow">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h4 class="mb-0 font-weight-bold">Variações</h4>
                                            </div>
                                            <div class="col text-right">
                                                <button type="button" class="btn btn-sm btn-success" @click="newOption()">
                                                    <i class="fas fa-plus mr-2"></i> Adicionar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Opção Pai</th>
                                                        <th>Opção Filho</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(option, i) in variant.options" :key="option.id">
                                                        <td>
                                                            <select class="form-control form-control-alternative" v-model="option.option_father">
                                                                <option :value="{values:[]}">- Selecione -</option>
                                                                <option v-for="option in options" :key="option.id" :value="option">
                                                                    {{ option.title }}
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control form-control-alternative" v-model="option.value">
                                                                <option :value="null">- Selecione -</option>
                                                                <option v-for="value in option.option_father.values" :key="value.id" :value="value">
                                                                    {{ value.title }}
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-icon-only" role="button" 
                                                                v-on:click="deleteVariantOption(i)">
                                                                <i class="fas fa-trash" ></i>
                                                            </a> 
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-shadow">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h4 class="mb-0 font-weight-bold">Estoques</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table align-items-center">
                                                    <thead>
                                                        <tr>
                                                            <th>Estoque</th>
                                                            <th>Qtd. Disp.</th>
                                                            <th v-if="variant.id">Reservado</th>
                                                            <th>Compra</th>
                                                            <th>Venda</th>
                                                            <th>Preço Promocional</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="stock in variant.stocks" :key="stock.id">
                                                            <th>
                                                                {{ (stock.title.length > 5) ? stock.title.slice(0, 5) + '...' : stock.title }}
                                                            </th>
                                                            <td>
                                                                <input type="number" class="form-control" v-model="stock.quantity">
                                                            </td>
                                                            <td v-if="variant.id">
                                                                {{ stock.reserved }}
                                                            </td>
                                                            <td>
                                                                <money v-bind="money" class="form-control" 
                                                                    v-model="stock.factory_price">
                                                                </money>
                                                            </td>
                                                            <td>
                                                                <money v-bind="money" class="form-control" v-model="stock.price"/>
                                                            </td>
                                                            <td>
                                                                <money v-bind="money" class="form-control" v-model="stock.promote_price"/>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-shadow">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h4 class="mb-0 font-weight-bold">Especificações</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="product_weight">Peso: *</label>
                                                    <input type="text" class="form-control" v-model="variant.weight" required>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="product_weight">SKU: *</label>
                                                    <input type="text" class="form-control" v-model="variant.sku" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-shadow">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h4 class="mb-0 font-weight-bold">Imagens</h4>
                                            </div>
                                            <div class="col text-right">
                                                <div class="button-wrapper">
                                                    <span class="btn btn-sm btn-success button" @click="$refs.file.click()">
                                                        <input type="file" ref="file" @change="uploadImage($event)" accept="image/*">
                                                        <i class="fas fa-plus mr-2" /> Adicionar
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 div-images"></div>
                                            <div class="col-md-4 div-images">
                                                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <div :class="[i == 0 ? 'active' : '', 'carousel-item', 'text-center']" v-for="(image, i) in variant.images" :key="image.id">
                                                            <img class="d-block img-variant" :src="image.url" alt="First slide">
                                                            <button type="button" class="btn btn-outline-danger excluir"  @click="variant.images.splice(i, 1)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 div-images"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MODAL PARA ADICIONAR IMAGENS -->
    <div class="modal fade" id="addImage" tabindex="-1" role="dialog" aria-labelledby="addImageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageLabel">Adicionar Imagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div v-if="errorImage" class="alert alert-warning" role="alert">
                        {{ errorImage }}
                    </div>
                    <Cropper
                        classname="upload-example-cropper"
                        :restrictions="pixelsRestriction"
                        :minHeight="200"
                        :minWidth="200"
                        :src="image"
                        ref="cropper"
                    />
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" @click="crop()" class="btn btn-success">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Categorias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="newCategory()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <select class="form-control" v-model="category.category_father">
                                        <option :value="{children:[]}">- Selecione -</option>
                                        <option v-for="category in categories" :key="category.id" :value="category">{{ category.title }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="category.category_father.children.length > 0">
                            <div class="col">
                                <div class="form-group focused">
                                    <select class="form-control form-control-alternative" v-model="category.category_son">
                                        <option :value="{children:[]}">- Selecione -</option>
                                        <option v-for="category in category.category_father.children" :key="category.id" :value="category">{{ category.title }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="category.category_son.children.length > 0">
                            <div class="col">
                                <div class="form-group focused">
                                    <select class="form-control form-control-alternative" v-model="category.category_grandchild">
                                        <option :value="{}">- Selecione -</option>
                                        <option v-for="category in category.category_son.children" :key="category.id" :value="category">{{ category.title }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>

import { showSuccessToast, showErrorToast, showInfoToast } from '../../../helpers/animations';
import Vue from 'vue';
import { Money } from 'v-money';
import { Cropper } from 'vue-advanced-cropper';

export default {
    components: {
        Cropper
    },

    props: ['_product'],

    data(){
        return{
            product: {
                enabled: null,
                self_delivery: null,
                variants: [],
                categories: [],
            },

            variant:{
                options: [],
                stocks: [],
                images: [],
            },

            category: {
                category_father: {
                    children: []
                },
                category_son: {
                    children: []
                },
                category_grandchild: { }
            },

            options: [],
            stocks: [],
            categories: [],
            
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                suffix: '',
                precision: 2,
                masked: false /* doesn't work with directive */
            },
            errorImage: null,
            image: null,
        }
    },

    methods: {

        openVariantModal: async function(){

            try{
                
                $("#variantModal").modal('show');

                await this.getOptions();
                await this.getStocks();

                this.stocks.map(stock => {
                    this.variant.stocks.push({
                        id: stock.id,
                        title: stock.title,
                        factory_price: stock.factory_price,
                        price: stock.price,
                        quantity: stock.quantity ? stock.quantity : 0,
                        promote_price: stock.promote_price  ? stock.promote_price : 0,
                        reserved: stock.reserved  ? stock.reserved : 0,
                    })
                });

            }catch(e){

                console.log(e);
                
            }
        },

        openEditVariant: async function(variant, i){
            try{
                
                await this.getOptions();
                await this.getStocks();

                this.variant = {
                    ...variant,
                    options: [],
                    index: i,
                }

                $("#variantModal").modal('show');

                
                let self = this;
                
                variant.values.forEach(value => {

                    let id = value.option ? value.option.id : value.option_father.id;

                    let option = self.options.find(option => option.id === id)
                    
                    if(option){

                        let value = option.values.find(value => value.id === value.id)
                        
                        self.variant.options.push({
                            id: Date.now() + value.id,
                            option_father: option,
                            value: value
                        });
                        
                    } else {

                        console.log("erro", variant.id)

                    }
                    
                });

                this.stocks.map(stock => {
                    
                    let s = self.variant.stocks.find(stockProduct => stockProduct.stock.id === stock.id);
                    
                    if(!s){
                        self.variant.stocks.push({
                            id: stock.id,
                            title: stock.title,
                            factory_price: stock.factory_price,
                            price: stock.price,
                            quantity: stock.quantity ? stock.quantity : 0,
                            saleprice: stock.saleprice,
                            reserved: stock.reserved,
                        })
                    }
                });


            }catch(e){

                console.log(e);
                
            }
        },

        openCategoriesModal: async function(){

            await this.getCategories();

            $("#categoriesModal").modal('show');

        },

        getCategories: async function(){

            try{

                const {data} = await axios.get('/painel/category');

                this.categories = data;

            }catch(e){
                console.log(e)
            }

        },

        getOptions: async function(){

            try{

                const {data} = await axios.get('/painel/options/all');

                this.options = data;

            }catch(e){

            }
        },

        getStocks: async function(){
            try{

                this.stocks = [];

                const {data} = await axios.get('/painel/stocks/all');

                this.stocks = data;


            }catch(e){

            }
        },

        newOption: function(){

            this.variant.options.push({
                option_father: {
                    values:[]
                },
                value: null,
            });

        },

        addVariant: function(){

            this.variant.options.map(option => {
                option.id = option.option_father.id;
                option.title = option.option_father.title;
                option.value_id = option.value.id;
                delete option.option_father.values;
                return option;
            });

            this.product.variants.push({
                sku: this.variant.sku,
                weight: this.variant.weight > 0 ? this.variant.weight : parseFloat(0.5),
                values: this.variant.options,
                stocks: this.variant.stocks,
                images: this.variant.images,
            });

            $("#variantModal").modal("hide");

            this.variant = {
                options: [],
                stocks: [],
                images: [],
            };

        },

        editVariant: function(){
            
            this.product.variants.splice(this.variant.index, 0);

            this.variant.options.map(option => {
                option.id = option.option_father.id;
                option.title = option.option_father.title;
                option.value_id = option.value.id;
                delete option.option_father.values;
                return option;
            });

            this.product.variants.push({
                sku: this.variant.sku,
                weight: this.variant.weight > 0 ? this.variant.weight : parseFloat(0.5),
                values: this.variant.options,
                stocks: this.variant.stocks,
                images: this.variant.images,
            });

            $("#variantModal").modal("hide");
            
            this.variant = {
                options: [],
                stocks: [],
                images: [],
            };

        },

        newCategory: function(){

            var title = this.category.category_father.title;
            var id = this.category.category_father.id;

            if(this.category.category_son.id){
                title = title + ' / ' + this.category.category_son.title;
                id = this.category.category_son.id;
            }

            if(this.category.category_grandchild.id){
                title = title + ' / ' + this.category.category_grandchild.title;
                id = this.category.category_grandchild.id;
            }

            this.product.categories.push({
                id: id,
                title: title
            });

            $("#categoriesModal").modal("hide");

            this.category = {
                category_father: {
                    children: []
                },
                category_son: {
                    children: []
                },
                category_grandchild: { }
            };  

        },

        uploadImage(event) {
			// Reference to the DOM input element
            var input = event.target;
            
			// Ensure that you have a file before attempting to read it
			if (input.files && input.files[0]) {

					// create a new FileReader to read this image and convert to base64 format
                    var reader = new FileReader();
                    
					// Define a callback function to run, when FileReader finishes its job
					reader.onload = (e) => {

							// Read image as base64 and set to imageData
							this.image = e.target.result;
                    }
                    
					// Start the reader job - read file as a data url (base64 format)
					reader.readAsDataURL(input.files[0]);
            }
            $("#addImage").modal({ backdrop: 'static', keyboard: false });
        },

		pixelsRestriction({minWidth, minHeight, maxWidth, maxHeight, imageWidth, imageHeight}) {
            
            this.errorImage = null;
            
            // se a imagem for menor que 200px
            if(imageHeight < 200 || imageWidth < 200){
                this.errorImage = "Essa imagem é muito pequena. Para melhor visualização do produto, opte por imagens com dimensões superiores a 600x600 px";
                return false;
            } 

            // se a imagem for maior que 200px e menor que 600px
            else if(imageHeight < 600 || imageWidth < 600) this.errorImage = "Dica: Para melhor visualização do produto, opte por imagens com dimensões superiores a 600px x 600px";
            
            return {
				minWidth: minWidth,
				minHeight: minHeight,
				maxWidth: Math.min(imageWidth, maxWidth),
				maxHeight: Math.min(imageHeight, maxHeight),
            }
            
        },

		crop() {

			const {coordinates, canvas} = this.$refs.cropper.getResult()
            this.coordinates = coordinates
            
            this.variant.images.push({
                id: Date.now(),
                url: canvas.toDataURL()
            });

            $("#addImage").modal("hide");

            this.image = null

		},

        store: async function(){
            try{

                const {data} = await axios.post('/painel/product', {
                    product: this.product
                });

            }catch(e){
                console.log(e);
            }
        },

        update: async function(){
            try{
                
                const {data} = await axios.patch(`/painel/product/${this.product.id}`, {
                    product: this.product
                });

                showSuccessToast('Produto salvo com sucesso.');


            }catch(e){
                showErrorToast('Não foi possivel salvar o produto.');
            }
        }
        

    },

    mounted() {
        
        if(this._product != null) this.product = this._product;

    },

}
</script>

<style>
    .button input {
        display: none;
    }

    .img-wrap {
        position: relative;
    }

    .img-wrap .close {
        position: absolute;
        top: 3px;
        left: 3px;
        z-index: 100;
    }
    .div-images .img-variant{
        max-height: 200px;
    }
    .modal-image{
        overflow-y: scroll !important
    }
    .modal-body-image{
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
</style>

