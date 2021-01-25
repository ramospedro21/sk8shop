<template>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col text-left">
                    <a class="btn btn-success btn-sm btn-round" href="/painel/clients">
                        <i class="fas fa-arrow-left mr-2"></i>voltar
                    </a>
                </div>
            </div>
            <form @submit.prevent="update()">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Sobre</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-1">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Nome: </label>
                                            <input type="text" class="form-control" v-model="client.name" required>
                                        </div>
                                    </div>
                                    <div class="col mt-1">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Email: </label>
                                            <input type="text" class="form-control" v-model="client.email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Data de nascimento: </label>
                                            <input type="text" class="form-control" v-model="client.details.birthdate" v-mask="'99/99/9999'" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">CPF: </label>
                                            <input type="text" class="form-control" 
                                                v-mask="'999.999.999-99'"
                                                v-model="client.details.tax_document_number" required
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">RG: </label>
                                            <input type="text" class="form-control" 
                                                v-mask="'99.999.999-9'"
                                                v-model="client.details.identity_document_number" required
                                            >
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Genêro: </label>
                                            <select name="" id="" class="form-control" v-model="client.details.gender" required>
                                                <option :value="null">-Selecione-</option>
                                                <option :value="0">Masculino</option>
                                                <option :value="1">Feminino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Telefone: </label>
                                            <input type="text" class="form-control" 
                                                v-model="client.details.cellphone" 
                                                v-mask="'(99)99999-9999'" 
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        Endereços
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Logradouro</th>
                                        <th>Bairro</th>
                                        <th>Numero</th>
                                        <th>Cep</th>
                                        <th>Cidade</th>
                                        <th>Estado</th>
                                    </tr>
                                    <tr v-for="address in client.address" :key="address.id">
                                        <td>{{ address.street }}</td>
                                        <td>{{ address.district }}</td>
                                        <td>{{ address.street_number }}</td>
                                        <td>{{ address.zipcode }}</td>
                                        <td>{{ address.city }}</td>
                                        <td>{{ address.state }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        Pedidos
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>ID</th>
                                            <th>Produtos</th>
                                            <th>Valor</th>
                                            <th>Efetuado em</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
  
</template>

<script>

import { showSuccessToast, showInfoToast, showErrorToast } from '../../../helpers/animations';
import moment from 'moment';

export default {

    props: ['_client'],

    data(){
        return{
            client: {}
        }
    },

    methods:{

    },

    mounted(){
        
        if(this._client){

            this.client = this._client;
        
        }
        
        if(this._client.details == null){
            this.client.details = {
                birthdate: null,
                tax_document_type: null,
                tax_document_number: '',
                identity_document_number: '',
                identity_document_type: '',
                phone: '',
                gender: null,
            }
        }else{
            this.client.details.birthdate = moment(this.client.details.birthdate).format('DD-MM-YYYY');
        }
    }

}
</script>

<style>

</style>