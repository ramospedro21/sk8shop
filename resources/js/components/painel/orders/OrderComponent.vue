<template>
<div>
    <div class="container-fluid mt-5 pt-5">
        <div class="header-body">
            <div class="row align-items-end">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mt-7 mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Produtos</h5>
                                    <span class="h4 font-weight-bold mb-0">{{ order.products.length }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mt-7 mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Envio</h5>
                                    <span class="h4 font-weight-bold mb-0">{{ order.delivery_service }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mt-7 mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Valor</h5>
                                    <span class="h4 font-weight-bold mb-0">{{ order.payment.amount | money }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mt-7 mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col" v-if="order.payment.gateway_status == 'AUTHORIZED' ">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Status</h5>
                                    <span class="h4 font-weight-bold mb-0">
                                        Pagamento Autorizado
                                    </span>
                                </div>
                                <div class="col" v-else>
                                    <h5 class="card-title text-uppercase text-muted mb-0">Status</h5>
                                    <span class="h4 font-weight-bold mb-0">
                                        {{ order.payment.gateway_status == 'WAITING' ? 'Aguardando Aprovação' : '' }}
                                        {{ order.payment.gateway_status == 'CANCELLED' ? 'Cancelado' : '' }}
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--8">
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-left">
                                <h3 class="mb-0">Produtos</h3>
                            </div>
                            <div class="col-md-8 text-right">
                                <h3 class="mb-0">Data do pedido: {{ order.created_at | datetime }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mb-4" v-for="product in order.products" :key="product.id">
                            <div class="col-5 mb-3 mb-md-0">
                                <img v-bind:src="product.variant.images.length > 0 ? product.variant.images[0].url : '/assets/imgs/no_product.png'" class="img-fluid">
                            </div>
                            <div class="col-md-7">
                                <p class="h4">{{ product.variant.title }}</p>
                                <p class="h6">SKU: {{ product.variant.sku }}</p>
                                <hr class="my-2">
                                <span>
                                    <p v-for="option in product.variant.values" :key="option.id" class="mb-0">
                                        <small>
                                            <span class="h5 mr-2">{{ option.option.title }}: </span> <i class="fas fa-circle mr-2"></i> {{ option.title }}
                                        </small>
                                    </p>
                                </span>
                                <hr class="my-2">
                                <p class="mb-0">
                                    <small>
                                        <span class="h5 mr-2">Quantidade: </span> {{ product.quantity }}
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <small>
                                        <span class="h5 mr-2">Valor: </span> {{ product.price | money }}
                                    </small>
                                </p>
                                <hr class="my-2">
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Cliente</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Nome: </span> {{ order.name }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Telefone: </span> {{ order.phone }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">E-mail: </span> {{ order.user.email }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">{{ order.tax_document_type }}: </span> {{ order.tax_document_number }}
                            </small>
                        </p>
                    </div>
                </div>
                <div class="card shadow mt-4">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="mb-0">Entrega</h3>
                            </div>
                            <div class="col-6 text-right" v-if="order.tracking_code == null && order.type == 1">
                                <button class="btn btn-block btn-success" type="button" data-toggle="modal" data-target="#modalTrackingCode">
                                    <i class="fas fa-plus mr-2"></i>Rastreio
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p v-if="order.type == 1" class="h4">{{ order.delivery_code }} - {{ order.delivery_service }}</p>
                        <p v-if="order.type == 2" class="h4">{{ order.payment.gateway_brand }}</p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">CEP: </span> {{ order.zipcode }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Logradouro: </span> {{ order.street }}, {{ order.street_number }} - {{ order.complement }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Bairro: </span> {{ order.district }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Cidade: </span> {{ order.city }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Estado: </span> {{ order.state }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Valor: </span> {{ order.payment.shipping_price | money }}
                            </small>
                        </p>
                        <p class="mb-0" v-if="order.tracking_code != null">
                            <small>
                                <span class="h5 mr-2">Código de rastreio: </span> {{ order.tracking_code }}
                            </small>
                        </p>
                    </div>
                </div>
                <div class="card shadow mt-4">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Financeiro</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Forma de Pagamento: </span>
                                {{ order.payment.gateway_brand == 'credit-card' ? 'Cartão de Crédito' : '' }}
                                {{ order.payment.gateway_brand == 'boleto' ? 'Boleto' : '' }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Status: </span>
                                {{ order.payment.gateway_status == 'WAITING' ? 'Aguardando Aprovação' : '' }}
                                {{ order.payment.gateway_status == '' ? 'Boleto' : '' }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Parcelas: </span>
                                {{ order.payment.installments }}
                            </small>
                        </p>
                        <p class="mb-0">
                            <small>
                                <span class="h5 mr-2">Valor Total: </span>
                                {{ order.payment.amount | money }}
                            </small>
                        </p>
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

    props: ['_order'],

    data: function() {
        return {
            order: {

            }
        }
    },

    methods: {

    },

    mounted : function() {

        if(this._order.id){
            this.order = this._order;
        }

    }

}
</script>

<style scoped>

</style>
