<template>
	<div class="container mt-5 pt-5">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-md-9">
								<h3 class="card-title h5">Cupons de desconto</h3>
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
                                    <th>Nome</th>
                                    <th>Limite por usuário</th>
                                    <th>Quantidade minima de produtos</th>
                                    <th>Alvo</th>
                                    <th></th>
                                </tr>
                            </table>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        <!-- MODAL DE CADASTRO E EDIÇÃO DE CUPONS  -->
        <div class="modal fade" id="couponsModal" tabindex="-1" role="dialog" aria-labelledby="couponLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="couponLabel">{{ coupon.id ? 'Editar cupom' : 'Adicionar cupom' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="coupon.id ? update() : store()">
                        <div class="modal-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Título: *</label>
                                        <input type="text" class="form-control" v-model="coupon.title" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Descrição: *</label>
                                        <input type="text" class="form-control" v-model="coupon.description" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">De: *</label>
                                        <input type="date" class="form-control" v-model="coupon.start_date" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Até: *</label>
                                        <input type="date" class="form-control" v-model="coupon.end_date" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Tipo de redução: *</label>
                                        <select class="form-control" v-model="coupon.type" required>
                                            <option :value="null">-Selecione-</option>
                                            <option :value="0">Porcentagem</option>
                                            <option :value="1">Dinheiro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Quantidade a reduzir: *</label>
                                        <input type="number" class="form-control" v-model="coupon.reduction_amount" required v-if="coupon.type != null && coupon.type == 0">
                                        <money v-bind="money" class="form-control" v-model="coupon.reduction_amount" required v-if="coupon.type != null && coupon.type == 1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Uso máximo: *</label>
                                        <input type="number" class="form-control" v-model="coupon.max_uses" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Limite por usuário: *</label>
                                        <input type="number" class="form-control" v-model="coupon.limit_per_user" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Quantidade minima de produtos: *</label>
                                        <input type="number" class="form-control" v-model="coupon.min_product_quantity" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Alvo da redução: *</label>
                                        <select class="form-control" v-model="coupon.target" required>
                                            <option :value="null">-Selecione-</option>
                                            <option :value="0">Preço</option>
                                            <option :value="1">Frete</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Valor Mínimo: *</label>
                                        <money v-bind="money" class="form-control" v-model="coupon.min_value" required />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Status: *</label>
                                        <select class="form-control" v-model="coupon.status" required>
                                            <option :value="null">-Selecione-</option>
                                            <option :value="0">Desativado</option>
                                            <option :value="1">Ativado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label">Apenas para primeira compra?: *</label>
                                        <select class="form-control" v-model="coupon.first_buy_only" required>
                                            <option :value="null">-Selecione-</option>
                                            <option :value="0">Não</option>
                                            <option :value="1">Sim</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-white">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btnAddBox" class="btn btn-success" :disabled="loading.buttonCoupon == true">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
	</div>
</template>

<script>

import {showSuccessToast, showErrorToast, showInfoToast} from '../../../helpers/animations';
import { Money } from 'v-money';

export default {

    data(){
        return{
            coupons: {
				total: 0,
				current_page: 1,
				data: [],
				last_page: 1,
				per_page: 16,
				from: 0,
				to: 0
            },

            coupon: {},

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

            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                suffix: '',
                precision: 2,
                masked: false /* doesn't work with directive */
            },
        }
    },

    methods:{

        index: async function(){

        },

        create: function(){

            this.coupon = {
                title: null,
                description: null,
                start_date: null,
                end_date: null,
                type: null,
                max_uses: null,
                limit_per_user: null,
                min_product_quantity: 1,
                target: null,
                reduction_amount: null,
                min_value: null,
                first_buy_only: null,
                status: null,
            }

            $("#couponsModal").modal('show');

        },

        store: async function(){

            this.loading.buttonCoupon = true;

            try{

                const {data} = await axios.post(`/painel/coupon`, {
                    coupon: this.coupon
                });

                // this.index();

                this.loading.buttonCoupon = false;

                // showSuccessToast('Cupom cadastrado com sucesso.');

                // $("#couponsModal").modal('hide');

            }catch(e){

                console.log(e);

            }

        },

        edit: function(){

        },

        update: async function(){

        },

        openDeleteModal: function(){

        },

        delete: async function(){

        }
    },

    mounted(){

    }

}
</script>

<style>

</style>