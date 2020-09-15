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
                                        <h4 class="mb-0">Detalhes para o envio</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Largura: *</label>
                                            <input type="text" class="form-control" v-model="product.width">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="form-control-label">Altura: *</label>
                                            <input type="text" class="form-control" v-model="product.heigth">
                                        </div>
                                    </div>
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
                                            <th>VARIAÇÕES</th>
                                            <th>SKU</th>
                                            <th>QTD. DISPONIVEL</th>
                                            <th>PREÇO</th>
                                            <th></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-shadow">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Imagens</h4>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" class="btn btn-success btn-sm btn-round">
                                            <i class="fas fa-plus mr-2"></i>Novo
                                        </button>
                                    </div>
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
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-lg btn-block">Salvar</button>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="addVariant" tabindex="-1" role="dialog" aria-labelledby="addVariantLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-secondary">
                <div class="modal-header bg-white">
                    <h5 class="modal-title" id="addVariantLabel">{{ variant.id ? 'Editar' : 'Adicionar' }} Variação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="variant.id ? editVariant(variant.index) : addVariant()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="card bg-secondary shadow">
                                    <div class="card-header bg-white border-0">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h3 class="mb-0">Variações</h3>
                                            </div>
                                            <div class="col text-right">
                                                <button type="button" class="btn btn-sm btn-success" @click="addOption()">
                                                    <i class="fas fa-plus mr-2"></i> Adicionar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Tipo</th>
                                                    <th scope="col">Nome</th>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(option, i) in options" :key="option.id">
                                                    <td scope="col">
                                                        <select class="form-control form-control-alternative" 
                                                            v-model="option.type">
                                                            <option :value="{values:[]}">- Selecione -</option>
                                                            <option v-for="type in options.data" :key="type.id" 
                                                                :value="type">
                                                                {{ type.title }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td scope="col">
                                                        <select class="form-control form-control-alternative" 
                                                        v-model="option.value">
                                                            <option :value="null">- Selecione -</option>
                                                            <option v-for="value in option.type.values" :key="value.id" 
                                                                :value="value">
                                                                {{ value.title }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-sm btn-icon-only text-success" role="button" 
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
                        <div class="row mt-4">
                            <div class="col">
                                <div class="card bg-secondary shadow">
                                    <div class="card-header bg-white border-0">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h3 class="mb-0">Estoque(s)</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Estoque</th>
                                                    <th scope="col">Qtd. Disp.</th>
                                                    <th scope="col" v-if="variant.id">Reservado</th>
                                                    <th scope="col">Compra</th>
                                                    <th scope="col">Venda</th>
                                                    <th scope="col">Promocional</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
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
                                                        <money v-bind="money" class="form-control" v-model="stock.saleprice"/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-secondary shadow mt-4">
                            <div class="card-header bg-white border-0">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="mb-0">Configurações Avançadas</h3>
                                    </div>
                                    <div class="col text-right">
                                        <a class="btn btn-sm btn-icon-only text-light" data-toggle="collapse" 
                                            href="#collapseConfigs" 
                                            role="button" 
                                            aria-expanded="false" 
                                            aria-controls="collapseConfigs">
                                            <i class="fas fa-sort-down" style="color: #000;"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="collapse mt-3" id="collapseConfigs">
                                    <div class="row ml-2">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="input-sku">SKU:</label>
                                            <input type="text" id="input-sku" class="form-control form-control-alternative"
                                                placeholder="SKU da Variação.." 
                                                v-model="variant.sku">
                                        </div>
                                        <div class="col">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="input-gtin_code">GTIN / EAN:</label>
                                                <input type="text" id="input-gtin_code" class="form-control form-control-alternative" placeholder="GTIN/EAN da Variação.." v-model="variant.gtin_code">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="input-depth" class="form-control-label">Peso (kg): *</label>
                                                <input type="number" step="0.01" id="input-depth" v-model="variant.weight" class="form-control form-control-alternative" placeholder="Digite aqui...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white">
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

export default {

    data(){
        return{
            product: {
                enabled: null,
                self_delivery: null,
            },

            options: [],
            stocks: []
        }
    },

    methods: {

        openVariantModal: function(){

            try{

                this.getOptions();
                this.getStocks();

                $("#variantModal").modal('show');

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
        }

    },

    mounted() {

    }

}
</script>

<style>

</style>