<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h3 class="card-title h5">Categorias</h3>
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
                        <li class="list-group-item" v-for="category in categories" :key="category.id">
                            <div class="row">
                                <div class="col-8">
                                    <b>{{ category.title }}</b>
                                    <i class="pointer fas fa-edit ml-4"  @click="edit(category)"></i>
                                    <i class="pointer fas fa-trash ml-3" @click="openDeleteModal(category)"></i>
                                    <i class="pointer fas fa-plus ml-3" @click="create(category)"></i>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="pointer fas fa-sort-down"
                                        style="color: #000;"
                                        data-toggle="collapse"
                                        v-if="category.children.length > 0"
                                        :href="'#category' + category.id"
                                    >
                                    </i>
                                </div>
                            </div>
                            <div class="collapse mt-2" :id="'category' + category.id">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item" v-for="child in category.children" :key="child.id">
                                        <div class="row px-0">
                                            <div class="col-8">
                                                {{ category.title }} / <b>{{ child.title }}</b>
                                                <i class="pointer fas fa-edit ml-4" v-on:click="edit(child, category)"></i>
                                                <i class="pointer fas fa-plus ml-3" v-on:click="create(child)"></i>
                                                <i class="pointer fas fa-trash ml-3" v-on:click="openDeleteModal(child)"></i>
                                            </div>
                                            <div class="col-4 pr-0 text-right">
                                                <i class="pointer fas fa-sort-down"
                                                    data-toggle="collapse"
                                                    :href="'#child' + child.id"
                                                    role="button"
                                                    aria-expanded="false"
                                                    :aria-controls="'child' + child.id"
                                                    v-if="child.children.length > 0"
                                                    style="color: #000;"></i>
                                            </div>
                                        </div>
                                        <div class="collapse mt-2" :id="'child' + child.id">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item pr-0" v-for="item in child.children" :key="item.id">
                                                    <div class="row px-0">
                                                        <div class="col-8">
                                                            {{ category.title }} / {{ child.title }} / <b>{{ item.title }}</b>
                                                            <i class="pointer fas fa-edit ml-3" v-on:click="edit(item, child)"></i>
                                                            <i class="pointer fas fa-trash ml-3" v-on:click="openDeleteModal(item)"></i>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE CADASTRO E EXCLUSÃO -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">{{ category.id ? 'Editar Categoria' : 'Cadastrar Categoria' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="category.id ? update() : store()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group focused" v-if="category.parent_title">
                                    <label class="form-control-label" for="input-parent">Categoria Pai:</label>
                                    <input type="text" id="input-parent" class="form-control" disabled v-model="category.parent_title" required>
                                </div>
                                <div class="form-group focused">
                                    <label class="form-control-label" for="input-name">Nome:</label>
                                    <input type="text" id="input-name" class="form-control" v-model="category.title" required>
                                </div>
                                <div class="form-group focused">
                                    <label class="form-control-label" for="input-name">Descrição:</label>
                                    <input type="text" id="input-name" class="form-control" v-model="category.description" required>
                                </div>
                                <div class="form-group focused">
                                    <label class="form-control-label" for="input-name">Deseja mostrar no site?</label>
                                    <select class="form-control" v-model="category.showing">
                                        <option :value="null">-Selecione-</option>
                                        <option :value="0">Não</option>
                                        <option :value="1">Sim</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="form-control-label" for="input-name">Vincular Cupom de Desconto:</label>
                                    <select name="select-coupon" id="select-coupon" class="form-control" v-model="category.coupon_id">
                                        <option :value="null">- Selecione -</option>
                                        <option v-for="coupon in coupons.data" :key="coupon.id" :value="coupon.id">{{ coupon.description }}</option>
                                    </select>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnSalvar" :disabled="loading.buttonCategories == true" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DE EXCLUSÃO -->
    <div class="modal fade bd-example-modal-sm" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">Exclusão de categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="destroy()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary h4">Deseja realmente excluir a categoria: "{{ this.category.title }}" ?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteCategorie == true">Sim, apagar</button>
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
        return {
            categories:[],

            category:{
                cupom_id: null
            },

            loading:{
                page: true,
                buttonCategories: false,
                buttonDeleteCategorie: false
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

            try{

                const {data} = await axios.get(`/painel/category`);

                this.categories = data;

                this.loading.page = false;

            }catch(e){

                this.loading.page = false;

                showErrorToast('Ocorreu um erro ao atualizar a lista de estoques');

            }
        },

        create: function(parent){

            this.category = {
                cupom_id: null
            }

            if(parent){
                this.category.parent_id = parent.id;
                this.category.parent_title = parent.title;
            }

            $("#categoryModal").modal('show');


        },

        store: async function(){

            this.loading.buttonCategories = true;

            try{

                const {data} = await axios.post(`/painel/category`, {category: this.category})

                this.loading.buttonCategories = false;

                this.index();

                showSuccessToast('Categoria cadastrada com sucesso.');

                $("#categoryModal").modal('hide');


            }catch(e){

                this.loading.buttonCategories = false;

                showErrorToast('Não foi possivel cadastrar a categoria');
            }
        },

        edit: function(category, parent){

            this.category = {
                ...category
            }

            if(parent){
                this.category.parent_id = parent.id;
                this.category.parent_title = parent.title;
            }

            $("#categoryModal").modal('show');
        },

        update: async function(){

            this.loading.buttonCategories = false;

            try{

                const {data} = await axios.patch(`/painel/category/${this.category.id}`, {category: this.category});

                this.loading.buttonCategories = false;

                this.index();

                showSuccessToast('Categoria editada com sucesso.');

                $("#categoryModal").modal('hide');

            }catch(e){

                this.loading.buttonCategories = false;

                showErrorToast('Não foi possivel cadastrar a categoria');

            }
        },

        openDeleteModal: function(category){
            this.category = {
                ...category
            },

            $("#deleteCategory").modal('show');
        },

        destroy: async function(){

            this.loading.buttonDeleteCategorie = true;

            try{

                await axios.delete(`/painel/category/${this.category.id}`);

                this.loading.buttonDeleteCategorie = false;

                this.index();

                showSuccessToast('Categoria apagada com sucesso.');

                $("#deleteCategory").modal('hide');

            }catch(e){

                this.loading.buttonDeleteCategorie = false;

                showErrorToast('Categoria apagada com sucesso.');
            }

        }
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
