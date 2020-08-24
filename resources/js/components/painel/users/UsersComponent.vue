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
                                <th>Telefone</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
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
                details:{},
                address:{
                    street: '',
                    district: '',
                    street_number: '',
                    complement: '',
                    zipcode: '',
                    state: '',
                    latitude: '',
                    longitude: '',
                }
            },

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
        index: async function(){

        },

        create: function(){

        },

        store: async function(){

        },
        
        edit: function(){

        },

        update: async function(){

        },

        openDeleteModal: function(){

        },

        delete: async function(){

        },
    },

    mounted(){

    }
}
</script>

<style>
    .pointer{
        cursor: pointer;
    }
</style>