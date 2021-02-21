<template>
	<div class="container mt-5 pt-5">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-md-7">
								<h3 class="card-title h5">Pedidos</h3>
							</div>
						</div>
					</div>
                    <div class="card-body">
                    	<div class="table-responsive">
                        	<table class="table">
                                <tr>
                                    <th>Nome</th>
                                    <th>Telefone.</th>
                                    <th>Serviço de entrega</th>
                                    <th>Valor</th>
                                    <th>QTD. Produtos</th>
                                    <th>Status</th>
                                </tr>
                                <tr v-for="order in orders.data" :key="order.id">
                                    <td>
                                        <a :href="`/painel/order/${order.id}`" class="font-weight-bold">
                                            {{ order.name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ order.phone }}
                                    </td>
                                    <td>
                                        {{ order.delivery_service }}
                                    </td>
                                    <td>
                                        {{ order.payment.amount | money  }}
                                    </td>
                                    <td>
                                        {{ order.products_count }}
                                    </td>
                                    <td>
                                        {{ order.status }}
                                    </td>
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
            orders: {
				total: 0,
				current_page: 1,
				data: [],
				last_page: 1,
				per_page: 16,
				from: 0,
				to: 0
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

                let url = '/painel/order?';

                url +=  '&page=' + ((page) ? page : this.orders.current_page);
                if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;

                const {data} = await axios.get(url);

                this.loading.page = false

                this.orders = data;

            }catch(e){
                showErrorToast('Não foi possivel listar os usuários');
            }
        },

        openDeleteModal: function(){

        },

        destroy: async function(){

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
