<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="container-fluid mt-5">
            <!-- <form @submit.prevent="update()"> -->
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
                                            <input type="text" class="form-control" v-model="product.short_desc">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">Descrição: *</label>
                                            <textarea cols="30" rows="10" v-model="product.desc" class="form-control">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Variações</h4>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-success btn-sm btn-round" @click="openVariantModal()">
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
                                                <span v-for="option in variant.options_values" :key="option.id">
                                                    {{ option.title }}; 
                                                </span>
                                            </td>
                                            <td>{{ variant.stocks[0].quantity }}</td>
                                            <td>{{ variant.stocks[0].price }}</td>
                                            <td>
                                                <i class="fas fa-edit" @click="openEditVariant(variant, i)"></i>
                                                <i class="fas fa-trash" @click="openDeleteVariant(variant, i)"></i>
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
                                    <div class="col-8">
                                        <h4 class="mb-0">Imagens</h4>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-success btn-sm btn-round">
                                            <i class="fas fa-plus mr-2"></i>Novo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-lg btn-block">Salvar</button>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="variantModal" tabindex="-1" role="dialog" aria-labelledby="variantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title" id="variantModalLabel">{{ variant.id ? 'Editar' : 'Adicionar' }} Variação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="variant.id ? editVariant(variant.index) : addVariant()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="card-shadow">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h4 class="mb-0">Variações</h4>
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
                        <hr>
                        <div class="row">
                            <div class="col">
                                <div class="card-shadow">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h4 class="mb-0">Estoques</h4>
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
                                                <h4 class="mb-0">Especificações</h4>
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
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
import { Money } from 'v-money';

export default {

    data(){
        return{
            product: {
                enabled: null,
                self_delivery: null,
                variants: []
            },
            variant:{
                options: [],
                stocks: []
            },

            options: [],
            stocks: [],
            
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                suffix: '',
                precision: 2,
                masked: false /* doesn't work with directive */
            },
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
                        saleprice: stock.saleprice,
                        reserved: stock.reserved,
                    })
                });

            }catch(e){

                console.log(e);
                
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
                id: Date.now(),
                sku: this.variant.sku,
                weight: this.variant.weight > 0 ? this.variant.weight : parseFloat(0.5),
                options_values: this.variant.options,
                stocks: this.variant.stocks,
            });

            $("#variantModal").modal("hide");

            this.variant = {
                options: [],
                stocks: []
            };

        }

    },

    mounted() {

    }

}
</script>

<style>

</style>