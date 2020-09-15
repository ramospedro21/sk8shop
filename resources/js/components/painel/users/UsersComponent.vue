<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h3 class="card-title h5">Usuários</h3>
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
                                <th>Email</th>
                                <th>Tipo de usuário</th>
                                <th></th>
                            </tr>
                            <tr v-for="user in users.data" :key="user.id">
                                <td>
                                    <a :href="'/painel/user/' + user.id" class="hover">{{ user.name }}</a>
                                </td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.user_type.title }}</td>
                                <td>
                                    <i class="pointer fas fa-trash" @click="openDeleteModal(user)"></i>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- MODAL DE CADASTRO DE USUÁRIO -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Cadastrar Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="store()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="form-control-label">Nome:</label>
                                <input type="text" class="form-control" placeholder="Informe o nome do tipo de usuário" v-model="user.name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-control-label">Email:</label>
                                <input type="text" class="form-control" placeholder="Informe o nome do tipo de usuário" v-model="user.email" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-8 mt-1">
                                <label for="" class="form-control-label">Senha:</label>
                                <input type="password" class="form-control" placeholder="Informe o nome do tipo de usuário" v-model="user.password" required>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-control-label">Tipo de usuário:</label>
                                <select name="" id="" class="form-control" v-model="user.user_type_id" required>
                                    <option :value="null">-Selecione-</option>
                                    <option v-for="ut in user_types" :key="ut.id" :value="ut.id">{{ ut.title }}</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="title" class="form-control-label">Cep:</label>
                                <input type="text" class="form-control" id="inputLogradouro" v-model="user.address.zipcode" required  v-on:blur="getAddress(user.address.zipcode)" v-mask="'99999-999'">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="title" class="form-control-label">Rua:</label>
                                <input type="text" class="form-control" v-model="user.address.street" required>
                            </div>
                            <div class="col-md-3">
                                <label for="title" class="form-control-label">Numero:</label>
                                <input type="text" class="form-control" v-model="user.address.street_number" required>
                            </div>
                            <div class="col-md-3">
                                <label for="title" class="form-control-label">Complemento:</label>
                                <input type="text" class="form-control" v-model="user.address.complement">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-5">
                                <label for="title" class="form-control-label">Bairro:</label>
                                <input type="text" class="form-control" v-model="user.address.district" required>
                            </div>
                            <div class="col-md-5">
                                <label for="title" class="form-control-label">Cidade:</label>
                                <input type="text" class="form-control" v-model="user.address.city" required>
                            </div>
                            <div class="col-md-2">
                                <label for="title" class="form-control-label">Estado:</label>
                                <input type="text" class="form-control" v-model="user.address.state" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonUser == true">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DE EXCLUSÃO DE USUÁRIO -->
    <div class="modal fade bd-example-modal-sm" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">Exclusão de usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="destroy()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary h4">Deseja realmente excluir o usuário: "{{ user.name }}"?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteUser == true">Sim, apagar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>

import {showSuccessToast, showErrorToast, showInfoToast} from '../../../helpers/animations';

export default {
    data(){
        return{
            users: {
				total: 0,
				current_page: 1,
				data: [],
				last_page: 1,
				per_page: 16,
				from: 0,
				to: 0
            },

            user:{
                user_type_id: null,
                address:{
                    street: '',
                    district: '',
                    zipcode: '',
                    state: '',
                }
            },

            user_types: [],

            loading:{
                page: true,
                buttonUser: false,
                buttonDeleteUser: false,
            },

            errors: {

            },

            filters: {
                per_page: 16
            },
        }
    },

    methods: {
        index: async function(page){
            try{

                let url = '/painel/user?';

                url +=  '&page=' + ((page) ? page : this.users.current_page);
                if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;

                const {data} = await axios.get(url);
                
                this.loading.page = false

                this.users = data;

            }catch(e){
                showErrorToast('Não foi possivel listar os usuários');
            }
        },

        create: function(){
            
            this.user = {
                user_type_id: null,
                address:{
                    street: '',
                    district: '',
                    zipcode: '',
                    state: '',
                }
            }
            
            $("#userModal").modal('show');

        },

        store: async function(){

            this.loading.buttonUser = true;

            try{

                const {data} = await axios.post(`/painel/user`, {user: this.user});

                this.index();

                this.loading.buttonUser = false;

                showSuccessToast('Usuário cadastrado com sucesso.');

                $("#userModal").modal('hide');


            }catch(e){

                this.loading.buttonUser = false;

                showErrorToast('Não foi possivel cadastrar o usuário');

            }
        },

        openDeleteModal: function(user){

            this.user = {
                ...user
            }

            $("#deleteUserModal").modal('show');
        },

        destroy: async function(){
            
            this.loading.buttonDeleteUser = true;
            
            try{

                await axios.delete(`/painel/user/${this.user.id}`);

                this.loading.buttonDeleteUser = false;

                this.index();

                showSuccessToast('Usuário apagado com sucesso.');
        
                $("#deleteUserModal").modal('hide');

            }catch(e){

                this.loading.buttonDeleteUser = false;

                showErrorToast('Não foi possivel apagar o usuário.');
            }
        },

        getUserTypes: async function(){

            try{

                const {data} = await axios.get('/painel/type/all');

                this.user_types = data;

            }catch(e){

                showErrorToast('Não foi possivel listar os tipos de usuário.');
                
            }
        },

        getAddress(cep){
            try{
                self = this;
    
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function(address){
                    console.log(address);
                    if(address.erro){
                        $("#inputLogradouro").focus();
                        return;
                    }
    
                    self.user.address.street = address.logradouro;
                    self.user.address.district = address.bairro;
                    self.user.address.city = address.localidade;
                    self.user.address.state = address.uf;
    
                });
            }catch(e){

                showErrorToast('Não foi possivel buscar o cep.');
            }
        },
    },

    mounted(){
        this.getUserTypes();
        this.index();
    }
}
</script>

<style>
    .pointer{
        cursor: pointer;
    }
    a:hover{
        color: red;
        transition: .35s ease;
    }
</style>