<template>
	<div class="container mt-5 pt-5">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-md-9">
								<h3 class="card-title h5">Fornecedores</h3>
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
									<th>Telefone</th>
									<th>Documento</th>
									<th>Compras feitas</th>
									<th></th>
								</tr>
								<tr v-for="provider in providers.data" :key="provider.id">
									<td>{{ provider.name }}</td>
									<td>{{ provider.phone }}</td>
									<td>{{ provider.tax_document_number }}</td>
									<td>{{ provider.purchases.length }}</td>
									<td>
										<i class="pointer fas fa-edit ml-3" @click="edit(provider)"></i>
										<i class="pointer fas fa-trash" @click="openDeleteModal(provider)"></i>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="providerModal" tabindex="-1" role="dialog" aria-labelledby="providerModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="providerModalLabel">{{ provider.id ? 'Editar Fornecedor' : 'Cadastrar Fornecedor' }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="provider.id ? update() : store()">
						<div class="modal-body">
							<div class="row mb-3">
								<div class="col-md-4">
									<label for="" class="form-control-label">Nome: </label>
									<input type="text" class="form-control" v-model="provider.name" required>
								</div>
								<div class="col-md-4">
									<label for="" class="form-control-label">Celular:</label>
									<input type="text" class="form-control" v-model="provider.phone" required v-mask="'(99)99999-9999'">
								</div>
								<div class="col-md-4">
									<label for="" class="form-control-label">Site:</label>
									<input type="text" class="form-control" v-model="provider.site">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label for="" class="form-control-label">Tipo de documento:</label>
									<select name="" id="" v-model="provider.tax_document_type" class="form-control">
										<option :value="null">-Selecione-</option>
										<option :value="0">CPF</option>
										<option :value="1">CNPJ</option>
									</select>
								</div>
								<div class="col-md-6 mt-1" v-if="provider.tax_document_type == 0">
									<label for="" class="form-control-label">Número do documento:</label>
									<input type="text" class="form-control" v-model="provider.tax_document_number" 
										v-mask="'999.999.999-99'">
								</div>
								<div class="col-md-6 mt-1"  v-else>
									<label for="" class="form-control-label">Número do documento:</label>
									<input type="text" class="form-control" v-model="provider.tax_document_number" 
										v-mask="'99.999.999/9999-99'">
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-4">
									<label for="title" class="form-control-label">Cep:</label>
									<input type="text" class="form-control" id="inputLogradouro" v-model="provider.zipcode" required  v-on:blur="getAddress(provider.zipcode)" v-mask="'99999-999'">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-6">
									<label for="title" class="form-control-label">Rua:</label>
									<input type="text" class="form-control" v-model="provider.street" required>
								</div>
								<div class="col-md-3">
									<label for="title" class="form-control-label">Numero:</label>
									<input type="text" class="form-control" v-model="provider.street_number" required>
								</div>
								<div class="col-md-3">
									<label for="title" class="form-control-label">Complemento:</label>
									<input type="text" class="form-control" v-model="provider.complement">
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-md-5">
									<label for="title" class="form-control-label">Bairro:</label>
									<input type="text" class="form-control" v-model="provider.district" required>
								</div>
								<div class="col-md-5">
									<label for="title" class="form-control-label">Cidade:</label>
									<input type="text" class="form-control" v-model="provider.city" required>
								</div>
								<div class="col-md-2">
									<label for="title" class="form-control-label">Estado:</label>
									<input type="text" class="form-control" v-model="provider.state" required>
								</div>
							</div>	
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
							<button type="submit" class="btn btn-success" :disabled="loading.buttonProvider == true">Salvar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal fade bd-example-modal-sm" id="deleteProviderModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="userTypeModalLabel">Exclusão de fornecedor</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="destroy()">
						<div class="modal-body">
							<div class="row">
								<div class="col-12">
									<p class="text-primary h4">Deseja realmente excluir o fornecedor: "{{ this.provider.name }}" ?</p>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
							<button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteProvider == true">Sim, apagar</button>
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
			providers: {
				total: 0,
				current_page: 1,
				data: [],
				last_page: 1,
				per_page: 16,
				from: 0,
				to: 0
			},

			provider: {
				tax_document_type: null,
				city: '',
				district: '',
				street: '',
				street_number: '',
				complement: '',
				zipcode: '',
				state: '',
			},

			loading:{
				page: true,
				buttonProvider: false,
				buttonDeleteProvider: false,
			},

			errors: {

			},

			filters:{
				per_page: 16
			}
		}
	},

	methods:{
		index: async function(page){

			let url = '/painel/provider?';
			url += '&page' + ((page) ? page : this.providers.current_page);
			if(this.filters.per_page != 16) url += '&per_page' + this.filters.per_page;

			try{

				const {data} = await axios.get(url);

                this.providers = data;

				this.loading.page = false;
				
			}catch(e){

                this.loading.page = false;

                showErrorToast('Ocorreu um erro ao atualizar a lista de estoques');
			}

		},

		create: function(){
			this.provider = {
				tax_document_type: null,
				city: '',
				district: '',
				street: '',
				street_number: '',
				complement: '',
				zipcode: '',
				state: '',
			},
			
			$("#providerModal").modal('show');

		},
		
		store: async function(){

			try{

				this.loading.buttonProvider = true;

				const {data} = await axios.post(`/painel/provider`, {provider: this.provider});

				this.loading.buttonProvider = false;

				showSuccessToast('Fornecedor cadastrado com sucesso.');

				this.index();

				$("#providerModal").modal('hide');

			}catch(e){

				this.loading.buttonProvider = false;

				showErrorToast('Erro ao cadastrar o fornecedor.')

			}
		},

		edit: function(provider){
			this.provider = {
				...provider
			};

			$("#providerModal").modal('show');
		},

		update: async function(){

			try{

				this.loading.buttonProvider = true;

				const {data} = await axios.patch(`/painel/provider/${this.provider.id}`, {provider: this.provider});

				this.loading.buttonProvider = false;

				showSuccessToast('Fornecedor editado com sucesso.');

				this.index();

				$("#providerModal").modal('hide');

			}catch(e){

				this.loading.buttonProvider = false;

				showErrorToast('Erro ao editar o fornecedor.')

			}
		},

		openDeleteModal: function(provider){
			this.provider = {
				...provider
			},

			$('#deleteProviderModal').modal('show');
		},

		destroy: async function(){
			this.loading.buttonDeleteProvider = true;

			try{
				
				await axios.delete(`/painel/provider/${this.provider.id}`);

				this.loading.buttonDeleteProvider = false;

				showSuccessToast('Fornecedor excluir com sucesso');

				this.index();

				$('#deleteProviderModal').modal('hide');


			}catch(e){
				this.loading.buttonDeleteProvider = false;

				showErrorToast('Não foi possivel excluir o fornecedor')
			}
		},
		

        getAddress(cep){
            self = this;

            $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function(address){

                if(address.erro){
                    $("#inputLogradouro").focus();
                    return;
                }

                self.provider.street = address.logradouro;
                self.provider.district = address.bairro;
                self.provider.city = address.localidade;
                self.provider.state = address.uf;

            });
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