<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h3 class="card-title h5">Central de Opções</h3>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary btn-block" @click="create()">
                                <i class="fa fa-plus mr-2"></i> Novo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" v-for="option in options.data" :key="option.id">
                            <div class="row">
                                <div class="col-8">
                                    <b>{{ option.title }}</b>
                                    <i class="pointer fas fa-edit ml-4"  @click="edit(option)"></i>
                                    <i class="pointer fas fa-trash ml-3" @click="openDeleteModal(option)"></i>
                                    <i class="pointer fas fa-plus ml-3" @click="create(option)"></i>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="pointer fas fa-sort-down"
                                        style="color: #000;"
                                        data-toggle="collapse" 
                                        v-if="option.values.length > 0"
                                        :href="'#option' + option.id"
                                    >
                                    </i>
                                </div>
                            </div>
                            <div class="collapse mt-2" :id="'option' + option.id">
                                <ul class="list-group list-group-flush mt-3">
                                    <li class="list-group-item" v-for="value in option.values" :key="value.id">
                                        <div class="row px-0">
                                            <div class="col-8">
                                                {{ value.title }}
                                                <i class="pointer fas fa-edit ml-3" @click="edit(option, value)"></i>
                                                <i class="pointer fas fa-trash ml-3" @click="openDeleteModal(value)"></i>
                                            </div>
                                        </div>
                                        <hr>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="optionModal" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionModalLabel">{{ option.id ? 'Editar Opção' : 'Nova Opção' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="!option.id ? store() : update()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group" v-if="option.parent_title">
                                    <label class="form-control-label" for="input-parent">Opção Pai:</label>
                                    <input type="text" id="input-parent" class="form-control" disabled v-model="option.parent_title">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">Nome:</label>
                                    <input type="text" id="input-name" class="form-control" placeholder="Nome da Opção.." v-model="option.title" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" :disabled="loading.buttonOptions == true" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DE EXCLUSÃO -->
    <div class="modal fade bd-example-modal-sm" id="deleteOptionModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">Exclusão de opção</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="destroy()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary h4">Deseja realmente excluir a opção: "{{ this.option.title }}" ?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteOption == true">Sim, apagar</button>
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
            
            options:{
                total: 0,
                current_page: 1,
                data: [],
                last_page: 1,
                per_page: 16,
                from: 0,
                to: 0
            },

            option:{
                title: '',
                values: []
            },
          
            loading:{
                page: true,
                buttonOptions: false,
                buttonDeleteOption: false
            },

            errors:{

            },
            
            filters:{
                per_page: 16
            }

        }
    },

    methods: {

        index: async function(page){

            let url = '/painel/option?';
            url += '&page=' + ((page) ? page : this.options.current_page);
            if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;
            
            try{

                const {data} = await axios.get(url);

                this.options = data;

                this.loading.page = false;

            }catch(e){

                this.loading.page = false;

                showErrorToast('Ocorreu um erro ao atualizar a lista de estoques');

            }
        },

        create: function(option){

            this.option = {
                title: '',
                values: []
            }

            if(option){
                this.option.parent_id = option.id;
                this.option.parent_title = option.title;
            }

            $("#optionModal").modal('show');
        },

        store: async function(){
            
            this.loading.buttonOptions = true;

            try{

                const {data} = await axios.post('/painel/option', {option: this.option})

                this.index();

                this.loading.buttonOptions = false;

                showSuccessToast('Opção Cadastrada com sucesso.');

                $("#optionModal").modal('hide');

            }catch(e){
                this.loading.buttonOptions = false;

                showErrorToast('Não foi possivel cadastrar a opção.');

            }
        },

        edit: function(option, value){
            
            if(value == undefined){

                this.option = {
                    ...option
                }

            }else{
                
                this.option = {
                    ...value
                }

                this.option.parent_title = option.title;
                this.option.parent_id = option.id;
            }

            $("#optionModal").modal('show');
        },

        update: async function(){
            
            this.loading.buttonOptions = true;
            
            try{
                
                const {data} = await axios.patch(`/painel/option/${this.option.id}`, {option: this.option});

                this.index();

                this.loading.buttonOptions = false;
                
                showSuccessToast('Opção editada com sucesso');

                $("#optionModal").modal('hide');

            }catch(e){

                this.loading.buttonOptions = false;

                showErrorToast('Não foi possivel editar a opção');

            }
        },

        openDeleteModal: function(option){

            this.option = {
                ...option
            }

            $("#deleteOptionModal").modal('show');

        },

        destroy: async function(){
            
            this.loading.buttonDeleteOption = true;

            try{

                await axios.delete(`/painel/option/${this.option.id}/${this.option.option_id ? 'value' : 'option'}`);

                this.index();

                this.loading.buttonDeleteOption = false;

                showSuccessToast('Opção apagada com sucesso.');

                $("#deleteOptionModal").modal('hide');

            }catch(e){

                this.loading.buttonDeleteOption = false;

                showErrorToast('Não foi possivel apagar a opção.');
            }

        },
    },

    mounted(){
        this.index();
    }
}
</script>

<style>
.pointer{
    cursor: pointer;
}
</style>