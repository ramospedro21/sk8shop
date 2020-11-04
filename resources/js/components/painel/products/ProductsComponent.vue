<template>
	<div class="container mt-5 pt-5">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-md-9">
								<h3 class="card-title h5">Produtos</h3>
							</div>
							<div class="col-md-3">
								<a class="btn btn-outline-secondary btn-block" :href="'/painel/product/new'">
									<i class="fa fa-plus mr-2"></i> Novo
								</a>
							</div>
						</div>
					</div>
                    <div class="card-body">
                    	<div class="table-responsive">
                        	<table class="table">
                                <tr>
                                    <th>Nome</th>
                                    <th>QTD. Disp.</th>
                                    <th>Mostrando no site?</th>
                                    <th></th>
                                </tr>
                                <tr v-for="product in products.data" :key="product.id">
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
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
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

        create: function(){
        },

        store: async function(){
        },

        openDeleteModal: function(){

        },

        delete: async function(){

        }

    },

    mounted(){
        this.index();
    }

}
</script>

<style>

</style>