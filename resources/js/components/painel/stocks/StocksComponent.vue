<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h3 class="card-title h5">Estoques</h3>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary btn-block" @click="create()">
                                <i class="fa fa-plus mr-2"></i> Novo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Nome</th>
                                <th>QTD. de produtos</th>
                                <th>Rua</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            <tr v-for="stock in stocks.data" :key="stock.id">
                                <td>{{ stock.title }}</td>
                                <td>{{ stock.products.length }}</td>
                                <td>{{ stock.street }}</td>
                                <td>{{ stock.status }}</td>
                                <td>
                                    <i class="fas fa-edit" @click="edit(stock)"></i>
                                    <i class="fas fa-trash ml-4" @click="openDestroyModal(stock)"></i>
                                </td>
                            </tr>
                        </table>
                     </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL DE CADASTRO E EDIÇÃO -->
    <div class="modal fade bd-example-modal-lg" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">{{ stock.id ? 'Editar estoque' : 'Cadastrar estoque' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="stock.id ? update() : store()">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4 mt-1">
                                <label for="title" class="form-control-label">Nome:</label>
                                <input type="text" class="form-control" v-model="stock.title" required>
                            </div>
                            <div class="col-md-4 mt-1">
                                <label for="title" class="form-control-label">Descrição:</label>
                                <input type="text" class="form-control" v-model="stock.description" required>
                            </div>
                            <div class="col-md-4">
                                <label for="title" class="form-control-label">Status:</label>
                                <select class="form-control" v-model="stock.status" required>
                                    <option :value="null">-Selecione-</option>
                                    <option :value="0">Ativo</option>
                                    <option :value="1">Desativado</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="title" class="form-control-label">Cep:</label>
                                <input type="text" class="form-control" id="inputLogradouro" v-model="stock.zipcode" required  v-on:blur="getAddress(stock.zipcode)" v-mask="'99999-999'">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="title" class="form-control-label">Rua:</label>
                                <input type="text" class="form-control" v-model="stock.street" required>
                            </div>
                            <div class="col-md-3">
                                <label for="title" class="form-control-label">Numero:</label>
                                <input type="text" class="form-control" v-model="stock.street_number" required>
                            </div>
                            <div class="col-md-3">
                                <label for="title" class="form-control-label">Complemento:</label>
                                <input type="text" class="form-control" v-model="stock.complement">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-5">
                                <label for="title" class="form-control-label">Bairro:</label>
                                <input type="text" class="form-control" v-model="stock.district" required>
                            </div>
                            <div class="col-md-5">
                                <label for="title" class="form-control-label">Cidade:</label>
                                <input type="text" class="form-control" v-model="stock.city" required>
                            </div>
                            <div class="col-md-2">
                                <label for="title" class="form-control-label">Estado:</label>
                                <input type="text" class="form-control" v-model="stock.state" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonStocks == true">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade bd-example-modal-sm" id="deleteStockModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">Exclusão de estoque</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="destroy()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary h4">Deseja realmente excluir o estoque: "{{ stock.title }}" ?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteStock == true">Sim, apagar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>

import { showErrorToast, showSuccessToast, showInfoToast } from "../../../helpers/animations";

export default {

    data(){
        return{
            stocks:{
                total: 0,
                current_page: 1,
                data: [],
                last_page: 1,
                per_page: 16,
                from: 0,
                to: 0
            },

            stock:{
                status: null,
                zipcode: '',
                street: '',
                district: '',
                street_number: '',
                complement: '',
                city: '',
                state: '',
            },
          
            loading:{
                page: true,
                buttonStocks: false,
                buttonDeleteStock: false
            },

            errors:{

            },
            
            filters:{
                per_page: 16
            }

        }
    },

    methods:{

        index: async function(page){

            let url = '/painel/stock?';
            url += '&page=' + ((page) ? page : this.stocks.current_page);
            if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;
            
            try{

                const {data} = await axios.get(url);

                this.stocks = data;

                this.loading.page = false;

            }catch(e){

                this.loading.page = false;

                showErrorToast('Ocorreu um erro ao atualizar a lista de estoques');

            }
        },

        create: function(){

            this.stock = { 
                status: null,
                zipcode: '',
                street: '',
                district: '',
                street_number: '',
                complement: '',
                city: '',
                state: '',
            }

            $("#stockModal").modal('show');
        },

        store: async function(){
            
            this.loading.buttonStocks = true;

            try{

                const {data} = await axios.post(`/painel/stock`, {stock: this.stock})
                
                this.loading.buttonStocks = false;

                this.index()

                showSuccessToast('Estoque cadastrado com sucesso.');

                $("#stockModal").modal('hide');

            }catch(e){

                showErrorToast('Não foi possivel cadastrar o estoque.');

                this.loading.buttonStocks = false;
            }
        },

        edit: function(stock){

            this.stock = {
                ...stock
            }

            if(this.stock.status == 'Ativo') this.stock.status = 0;
            if(this.stock.status == 'Desativado') this.stock.status = 1;

            $("#stockModal").modal('show');

        },

        update: async function(){
            
            this.loading.buttonStocks = true;

            try{

                const {data} = await axios.patch(`/painel/stock/${this.stock.id}`, {stock: this.stock})
                
                this.loading.buttonStocks = false;

                this.index()

                showSuccessToast('Estoque editado com sucesso.');

                $("#stockModal").modal('hide');

            }catch(e){

                showErrorToast('Não foi possivel editar o estoque.');

                this.loading.buttonStocks = false;
            }
        },

        openDestroyModal: function(stock){
            this.stock = {
                ...stock,
            };

            $("#deleteStockModal").modal('show');
        },

        destroy: async function(){

            this.loading.buttonDeleteStock = true;

            try{
                await axios.delete(`/painel/stock/${this.stock.id}`);
                
                this.loading.buttonDeleteStock = false;

                showSuccessToast('Estoque excluido com sucesso.');
                
                this.index();

                $("#deleteStockModal").modal('hide');

            }catch(e){
                
                this.loading.buttonDeleteStock = false;
                showSuccessToast('Não foi possivel excluir o estoque.');
            }

        },

        getAddress(cep){
            self = this;

            $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function(address){

                if(address.erro){
                    $("#inputLogradouro").focus();
                    return;
                }

                self.stock.street = address.logradouro;
                self.stock.district = address.bairro;
                self.stock.city = address.localidade;
                self.stock.state = address.uf;

            });
        },
    },

    mounted(){
        this.index();
    }
}
</script>

<style>

</style>