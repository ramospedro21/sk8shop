<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h3 class="card-title h5">Tipos de Usuário</h3>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary btn-block" @click="create()">
                                <i class="fa fa-plus mr-2"></i> Novo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-if="loading.page == false">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th></th>
                            </tr>
                            <tr v-for="ut in user_types.data" :key="ut.id">
                                <th scope="row">{{ ut.id }}</th>
                                <td>{{ ut.title }}</td>
                                <td>
                                    <i class="fas fa-edit" @click="edit(ut)"></i>
                                    <i class="fas fa-trash ml-4" @click="deleteUserType(ut)"></i>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE CADASTRO E ATUALIZAÇÃO -->
  
    <div class="modal fade" id="userTypeModal" tabindex="-1" role="dialog" aria-labelledby="userTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">{{ user_type.id ? 'Editar tipo de usuário' : 'Cadastrar tipo de usuário' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="user_type.id ? update() : store()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="" class="form-control-label">Nome:</label>
                                <input type="text" class="form-control" placeholder="Informe o nome do tipo de usuário" v-model="user_type.title">
                            </div>
                            <div class="col-md-12 py-3">
                                <label for="" class="form-control-label">Módulos Permitidos</label>
                                <div v-for="m in modules" :key="m.id" class="row">
                                    <div class="col border-bottom py-2">
                                        <a role="button" data-toggle="collapse" :href="'#moduleOptionCollapse' + m.id" aria-expanded="false" class="nav-link">
                                            {{ m.name }}
                                        </a>
                                        <div class="collapse" :id="'moduleOptionCollapse' + m.id">
                                            <div v-for="subm in m.values" :key="subm.id" class="form-check ml-4">
                                                <label class="form-control-label">
                                                    <input class="form-check-input" type="checkbox" v-model="user_type.modules" :value="subm">
                                                    {{ subm.name }}
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonUserType == true">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DE EXCLUSÃO -->
    
    <div class="modal fade bd-example-modal-sm" id="deleteUserTypeModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">Exclusão de tipo de usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="destroy()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary h4">Deseja realmente excluir o tipo de usário: "{{ user_type.title }}" ?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.deleteButtonUserType == true">Sim, apagar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>

import {showErrorToast, showInfoToast, showSuccessToast} from "../../../helpers/animations";

export default {
    data(){
        return{
            user_types:{
                total: 0,
                current_page: 1,
                data: [],
                last_page: 1,
                per_page: 16,
                from: 0,
                to: 0
            },
            
            loading:{
                page: true,
                buttonUserType: false,
                deleteButtonUserType: false
            },
            
            errors:{},
            
            user_type:{
                title: null,
                modules: []
            },

            modules: [],

            filters:{
                per_page: 16
            }
        }
    },

    methods: {
        index: async function(page){

                let url = '/painel/user_types?';
                url += '&page=' + ((page) ? page : this.user_types.current_page);
                if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;

                try {

                    const {data} = await axios.get(url);
    
                    this.user_types = data;

                    this.getModules();

                    this.loading.page = false;

                } catch (e) {

                    this.loading.page = false;

                    showErrorToast('Ocorreu um erro ao atualizar a lista de fornecedores');
                }

        },

        // FUNÇÃO PARA ZERAR O OBJETO USER_TYPE E ABRIR O MODAL
        create: function(){

            this.user_type = {
                title: null,
                modules: [],
            }

            $("#userTypeModal").modal('show');
        },

        store: async function(){
            
            if(!this.user_type.title){
                showErrorToast('Informe o nome do tipo de usuário');
                return false;
            }

            this.loading.buttonUserType = true;

            try{

                const {data} = await axios.post('/painel/user_types', {user_type: this.user_type});

                this.index();

                $("#userTypeModal").modal('hide');

                showSuccessToast('Tipo de usuário salvo com sucesso.');

                this.loading.buttonUserType = false;

            }catch(e){

                showErrorToast('Não foi possivel salvar o tipo de usuário.');

                this.loading.buttonUserType = false;
            }
        },

        edit: function(ut){

            this.user_type = {
                ...ut,
            };

            $("#userTypeModal").modal("show");
        },

        update: async function(){
            
            if(!this.user_type.title){
                showErrorToast('Informe o nome do tipo de usuário');
                return false;
            }

            this.loading.buttonUserType = true;

            try{

                const { data } = await axios.patch(`/painel/user_types/${this.user_type.id}`, {user_type: this.user_type});

                this.index();

                $("#userTypeModal").modal('hide');

                showSuccessToast('Tipo de usuário editado com sucesso');

                this.loading.buttonUserType = false;

            }catch(e){

                showErrorToast(data.message);

                this.loading.buttonUserType = false;

            }
        },

        deleteUserType: function(ut){

            this.user_type = {
                ...ut,
            };

            $("#deleteUserTypeModal").modal("show");
        },

        destroy: async function(){

            try{

                this.loading.deleteButtonUserType = true;

                await axios.delete(`/painel/user_types/${this.user_type.id}`);

                this.index();

                
                this.loading.deleteButtonUserType = false;

                showSuccessToast('Tipo de usuário apagado com sucesso');

                $("#deleteUserTypeModal").modal("hide");
                
            }catch(e){

                this.loading.deleteButtonUserType = false;

                showErrorToast('Não foi possível apagar o tipo de usuário');

            }
        },

        getModules: async function(){

            try{

                const {data} = await axios.get('/painel/modules/all');

                this.modules = data;

            }catch(e){

                console.log(e);
                
            }
        }
    },

    mounted(){
        this.index();
    }
}
</script>

<style>

</style>