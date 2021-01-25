<template>
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h3 class="card-title h5">Clientes</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responside">
                            <table class="table">
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Compras</th>
                                    <th></th>
                                </tr>
                                <tr v-for="client in clients.data" :key="client.id">
                                    <td> {{ client.id }} </td>
                                    <td>  
                                        <a :href="'/painel/client/' + client.id">
                                            {{ client.name }} 
                                        </a>
                                    </td>
                                    <td> {{ client.email }} </td>
                                    <td>  </td>
                                    <td> {{ client.orders_count }} </td>
                                    <td>
                                        <i class="fas fa-edit"></i>
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
            clients: {
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
                buttonCoupon: false,
                buttonDeleteCoupon: false,
            },

            errors: {

            },

            filters: {
                per_page: 16
            },
        }
    },

    methods: {

        index: async function(page = 1){

            let url = '/painel/client?';
            
            url += '&page=' + ((page) ? page : this.coupons.current_page);

            if (this.filters.per_page !== 16) url += '&per_page=' + this.filters.per_page;
            
            try{

                const {data} = await axios.get(url);

                this.clients = data;

                this.loading.page = false;

            }catch(e){

                this.loading.page = false;

                showErrorToast('Ocorreu um erro ao atualizar a lista de estoques');

            }
        },

    },

    mounted(){
        this.index();
    }

}
</script>

<style>

</style>