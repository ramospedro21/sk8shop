<template>
	<div class="container mt-5 pt-5">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-md-9">
								<h3 class="card-title h5">Embalagens</h3>
							</div>
							<div class="col-md-3">
								<a class="btn btn-outline-secondary btn-block" @click="create()">
									<i class="fa fa-plus mr-2"></i> Novo
								</a>
							</div>
						</div>
					</div>
                    <div class="card-body">
                    	<div class="table-responsive">
                        	<table class="table">
                                <tr>
                                    <th>#</th>
                                    <th>Titulo</th>
                                    <th>Altura</th>
                                    <th>Largura</th>
                                    <th>Profundidade</th>
                                    <th>QTD.</th>
                                    <th></th>
                                </tr>
                                <tr v-for="box in boxes.data" :key="box.id">
                                    <td>{{box.id}}</td>
                                    <td>{{box.title}}</td>
                                    <td>{{box.height}}</td>
                                    <td>{{box.width}}</td>
                                    <td>{{box.depth}}</td>
                                    <td>{{box.quantity}}</td>
                                    <td>
                                        <i class="fas fa-edit pointer" @click="show(box)"></i>
                                        <i class="fas fa-trash pointer" @click="openDeleteModal(box)"></i>
                                    </td>
                                </tr>
                        	</table>
                    	</div>
                    </div>
				</div>
			</div>
		</div>
        <!-- MODAL DE CADASTRO E EDIÇÃO DE EMBALAGENS  -->
        <div class="modal fade" id="boxesModal" tabindex="-1" role="dialog" aria-labelledby="addBoxLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBoxLabel">{{ box.id ? 'Editar embalagem' : 'Adicionar embalagem' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="box.id ? update() : store()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Descrição:</label>
                                        <input type="text" id="input-desc" class="form-control" placeholder="Digite o nome da embalagem" v-model="box.title" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Quantidade:</label>
                                        <input type="text" id="input-desc" class="form-control" placeholder="Digite o nome da embalagem" v-model="box.quantity" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Largura:</label>
                                        <input type="number" id="input-width" step="0.01" class="form-control" placeholder="Digite a largura.." v-model="box.width" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Altura:</label>
                                        <input type="number" id="input-height" step="0.01" class="form-control" placeholder="Digite a altura.." v-model="box.height" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Profundidade:</label>
                                        <input type="number" id="input-depth" step="0.01" class="form-control" placeholder="Digite a profundidade.." v-model="box.depth" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-white">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btnAddBox" class="btn btn-success" :disabled="loading.buttonBox == true">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-sm" id="deleteBoxModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userTypeModalLabel">Exclusão de embalagem</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="destroy()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-primary h4">Deseja realmente excluir a embalagem: "{{ this.box.title }}" ?</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteBox == true">Sim, apagar</button>
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
            boxes: {
				total: 0,
				current_page: 1,
				data: [],
				last_page: 1,
				per_page: 16,
				from: 0,
				to: 0
            },

            box: {
                title: '',
                quantity: 0,
                width: null,
                depth: null,
                height: null,
            },

            loading:{
                page: true,
                buttonBox: false,
                buttonDeleteBox: false,
            },

            errors: {

            },

            filters: {
                per_page: 16
            },
        }
    },

    methods:{

        index: async function(page){

            try{
                let url = '/painel/box?';

                url +=  '&page=' + ((page) ? page : this.boxes.current_page);
                if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;

                const {data} = await axios.get(url);
                
                this.loading.page = false

                this.boxes = data;

            }catch(e){
                showErrorToast('Não foi possivel listar os usuários');
            }
        },

        create: function(){

            this.box = {
                title: '',
                quantity: 0,
                width: null,
                depth: null,
                height: null,
            }

            $("#boxesModal").modal('show');
        },

        store: async function(){

            this.loading.buttonBox = true;

            try{

                const {data} = await axios.post(`/painel/box`, {box: this.box});

                this.loading.buttonBox = false;

                showSuccessToast('Embalagem cadastrada com sucesso.');

                $("#boxesModal").modal('hide');

            }catch(e){
                
                this.loading.buttonBox = false; 

                showErrorToast('Não foi possivel cadastrar a embalagem.');
            }
        },

        show: function(box){
            
            this.box = {
                ...box
            }

            $("#boxesModal").modal('show');

        },

        update: async function(){
            
            this.loading.buttonBox = true;

            try{

                const {data} = await axios.patch(`/painel/box/${this.box.id}`,{
                    box: this.box
                });

                this.index();

                this.loading.buttonBox = false;

                showSuccessToast('Embalagem editada com sucesso.');

                $("#boxesModal").modal('hide');

            }catch(e){

                this.loading.buttonBox = false;
                
                showErrorToast('Não foi possivel editar a embalagem.');

            }

        },

        openDeleteModal: function(box){

            this.box = {
                ...box
            }

            $("#deleteBoxModal").modal('show');
        },

        destroy: async function(){

            this.loading.buttonDeleteBox = true;

            try{

                await axios.delete(`/painel/box/${this.box.id}`)

                this.index();

                this.loading.buttonDeleteBox = false;

                $("#deleteBoxModal").modal('hide');

                showSuccessToast('Embagalem excluida com sucesso.');

            }catch(e){

                this.loading.buttonDeleteBox = false;

                showErrorToast('Não foi possivel excluir a embalagem.');

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