<template>
	<div class="container mt-5 pt-5">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-md-7">
								<h3 class="card-title h5">Produtos</h3>
							</div>
							<div class="col-md-5 text-right">
                                <button class="btn btn-outline-secondary mr-4" v-if="productsToDelete.length > 0"
                                        data-target="#deleteProductModal"
                                        data-toggle="modal">
                                    <i class="fas fa-trash mr-2"></i> Excluir
                                </button>
								<a class="btn btn-outline-secondary" :href="'/painel/product/new'">
									<i class="fa fa-plus mr-2"></i> Novo
								</a>
							</div>
						</div>
					</div>
                    <div class="card-body">
                    	<div class="table-responsive">
                        	<table class="table">
                                <tr>
                                    <td></td>
                                    <th>Nome</th>
                                    <th>QTD. Disp.</th>
                                    <th>Mostrando no site?</th>
                                    <th></th>
                                </tr>
                                <tr v-for="product in products.data" :key="product.id">
                                    <td>
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input class="custom-control-input" :id="'select-product-' + product.id" v-model="productsToDelete" type="checkbox" :value="product.id">
                                            <label class="custom-control-label" :for="'select-product-' + product.id"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a :href="`/painel/product/${product.id}`">
                                            {{ product.title }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ product.quantity }}
                                    </td>
                                    <td>
                                        {{ product.enabled }}
                                    </td>
                                    <td>

                                        <i class="fas fa-trash pointer" @click="openDeleteModal(product.id)"></i>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        <div class="modal fade bd-example-modal-sm" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userTypeModalLabel">Exclusão de produto(s)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="destroy()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12" v-if="this.productsToDelete.length == 0">
                                    <p class="text-primary h4">Deseja realmente excluir o produto: "{{ this.product.id }}"?</p>
                                </div>
                                <div class="col-12" v-else>
                                    <p class="text-primary h4">Deseja realmente excluir {{ this.productsToDelete.length }} produtos?</p>
                                </div>
                                <div class="col-12">
                                    <p class="text-primary h4">Com isso todas as variações, estoques e configurações de produto serão excluidas</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteProduct == true">Sim, apagar</button>
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
            products: {
				total: 0,
				current_page: 1,
				data: [],
				last_page: 1,
				per_page: 16,
				from: 0,
				to: 0
            },

            product: {
                id: null
            },

            productsToDelete: [],

            loading:{
                page: true,
                buttonProduct: false,
                buttonDeleteProduct: false,
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

                let url = '/painel/product?';

                url +=  '&page=' + ((page) ? page : this.products.current_page);
                if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;

                const {data} = await axios.get(url);

                this.loading.page = false

                this.products = data;

            }catch(e){
                showErrorToast('Não foi possivel listar os usuários');
            }
        },

        openDeleteModal: function(id){
            this.product.id = id;

            $("#deleteProductModal").modal('show');
        },

        destroy: async function(){

            this.loading.buttonDeleteProduct = true;

            try{


                await axios.post(`/painel/product/delete`,{
                    id: this.product.id,
                    productsToDelete: this.productsToDelete
                });

                this.index();

                this.product.id = null;

                this.loading.buttonDeleteProduct = false;

                showSuccessToast('Produto(s) deletado(s) com sucesso');

                $("#deleteProductModal").modal('hide');

            }catch(e){

                this.loading.buttonDeleteProduct = false;

                showErrorToast('Não foi possível excluir o produto');

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
