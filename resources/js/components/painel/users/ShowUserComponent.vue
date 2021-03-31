<template>
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col text-left">
                    <a class="btn btn-success btn-sm btn-round" href="/painel/users">
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
                                            <input type="text" class="form-control" v-model="user.name" required>
                                        </div>
                                    </div>
                                    <div class="col mt-1">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Email: </label>
                                            <input type="text" class="form-control" v-model="user.email" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Tipo de Usuário: </label>
                                            <select name="" id="" class="form-control" v-model="user.user_type_id" required>
                                                <option :value="null">-Selecione-</option>
                                                <option v-for="ut in user_types" :key="ut.id" :value="ut.id">{{ ut.title }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow mt-3">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Endereços</h4>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <a class="btn btn-outline-secondary btn-sm btn-round" @click="openAddressModal()">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Logradouro</th>
                                            <th>Bairro</th>
                                            <th>Numero</th>
                                            <th>Cep</th>
                                            <th>Cidade</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(address, i) in user.address" :key="address.id">
                                            <td>{{ address.street }}</td>
                                            <td>{{ address.district }}</td>
                                            <td>{{ address.street_number }}</td>
                                            <td>{{ address.zipcode }}</td>
                                            <td>{{ address.city }}</td>
                                            <td>
                                                <i class="fas fa-edit" @click="editAddress(address, i)"></i>
                                                <i class="fas fa-trash ml-2" @click="confirmDeleteAddress(address, i)"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <h4 class="mb-0">Detalhes</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Data de nascimento: </label>
                                            <input type="text" class="form-control" v-model="user.details.birthdate" v-mask="'99/99/9999'" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">CPF: </label>
                                            <input type="text" class="form-control"
                                                v-mask="'999.999.999-99'"
                                                v-model="user.details.tax_document_number" required
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
                                                v-model="user.details.identity_document_number" required
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="input-title" class="form-control-label">Telefone: </label>
                                            <input type="text" class="form-control"
                                                v-model="user.details.phone"
                                                v-mask="'(99)99999-9999'"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE CADASTRO DE ENDEREÇO -->
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">Cadastrar Endereço</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="pushAddress()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="title" class="form-control-label">Cep:</label>
                                <input type="text" class="form-control" id="inputLogradouro" v-model="address.zipcode" required  v-on:blur="getAddress(address.zipcode)" v-mask="'99999-999'">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="title" class="form-control-label">Rua:</label>
                                <input type="text" class="form-control" v-model="address.street" required>
                            </div>
                            <div class="col-md-3">
                                <label for="title" class="form-control-label">Numero:</label>
                                <input type="text" class="form-control" v-model="address.street_number" required>
                            </div>
                            <div class="col-md-3">
                                <label for="title" class="form-control-label">Complemento:</label>
                                <input type="text" class="form-control" v-model="address.complement">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-5">
                                <label for="title" class="form-control-label">Bairro:</label>
                                <input type="text" class="form-control" v-model="address.district" required>
                            </div>
                            <div class="col-md-5">
                                <label for="title" class="form-control-label">Cidade:</label>
                                <input type="text" class="form-control" v-model="address.city" required>
                            </div>
                            <div class="col-md-2">
                                <label for="title" class="form-control-label">Estado:</label>
                                <input type="text" class="form-control" v-model="address.state" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.address == true">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DE EXCLUSÃO DE ENDEREÇO -->
    <div class="modal fade bd-example-modal-sm" id="deleteAddressModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userTypeModalLabel">Exclusão de Endereço</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="deleteAddress()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary h4">Deseja realmente excluir o Endereço: "{{ address.street }}"?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="loading.buttonDeleteAddress == true">Sim, apagar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>

import { showSuccessToast, showErrorToast, showInfoToast } from '../../../helpers/animations';
import moment from 'moment';

export default {
    props: ['_user'],

    data(){
        return {
            user: {
                details: {
                    birthdate: '',
                    tax_document_type: null,
                    tax_document_number: '',
                    identity_document_number: '',
                    identity_document_type: '',
                    phone: '',
                },
                address: []
            },

            address: {
                city: '',
                complement: '',
                country: '',
                created_at: '',
                district: '',
                state: '',
                street: '',
                street_number: '',
                zipcode: '',
                index: null
            },

            loading: {
                buttonAddress: null,
                buttonUser: null
            },

            user_types: [],
        }
    },

    methods: {
        update: async function(){


            this.loading.buttonUser = true;

            try{

                const {data} = axios.patch(`/painel/user/${this.user.id}`, {user: this.user});

                this.loading.buttonUser = true;

                showSuccessToast('Usuário editado com sucesso');

                // setTimeout(function () {
                //     location.href = '/painel/users/';
                // }, 800)

            }catch(e){

                this.loading.buttonUser = true;

                showErrorToast('Não foi possivel editar o usuário');
            }

        },

        getUserTypes: async function(){

            try{

                const {data} = await axios.get('/painel/type/all');

                this.user_types = data;

            }catch(e){

                showErrorToast('Não foi possivel listar os tipos de usuário.');

            }
        },

        openAddressModal: function(){

            this.address = {
                city: '',
                complement: '',
                country: '',
                created_at: '',
                district: '',
                state: '',
                street: '',
                street_number: '',
                zipcode: '',
            }

            $("#addressModal").modal('show');
        },

        editAddress: function(address, i){

            this.address = {
                ...address,
                index: i
            }

            $("#addressModal").modal('show');
        },

        getAddress(cep){
            try{
                self = this;

                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function(address){

                    if(address.erro){
                        $("#inputLogradouro").focus();
                        return;
                    }

                    self.address.street = address.logradouro;
                    self.address.district = address.bairro;
                    self.address.city = address.localidade;
                    self.address.state = address.uf;

                });
            }catch(e){
                showErrorToast('Não foi possivel buscar o cep.');
            }
        },

        pushAddress: function(){

            if(this.address.index != null) this.user.address.splice(this.address.splice, 1)

            this.user.address.push({
                city: this.address.city,
                complement: this.address.complement,
                country: this.address.country,
                created_at: this.address.created_at,
                district: this.address.district,
                state: this.address.state,
                street: this.address.street,
                street_number: this.address.street_number,
                zipcode: this.address.zipcode,
            });

            $("#addressModal").modal('hide');
        },

        confirmDeleteAddress: function(address, index){
            this.address = {
                ...address,
                index: index
            }

            $("#deleteAddressModal").modal('show');
        },

        deleteAddress: function(){

            this.loading.buttonDeleteAddress = true;

            try{

                this.user.address.splice(this.address.index, 1);

                showSuccessToast('Endereço apagado com sucesso.');

                this.loading.buttonDeleteAddress = false;

                $("#deleteAddressModal").modal('hide');

            }catch(e){
                this.loading.buttonDeleteAddress = false;

                showErrorToast('Não foi possivel apagar o endereço');
            }
        }

    },

    mounted(){

        this.user = this._user;

        if(this._user.details == null){
            this.user.details = {
                birthdate: '',
                tax_document_type: null,
                tax_document_number: '',
                identity_document_number: '',
                identity_document_type: '',
                phone: '',
            }
        }else{
            this.user.details.birthdate = moment(this.user.details.birthdate).format('DD-MM-YYYY');
        }
        this.getUserTypes();
    }
}
</script>

<style>

</style>
