/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

require('./bootstrap');

require('./helpers/cep');
import moment from 'moment';

const VueInputMask = require('vue-inputmask').default

Vue.use(VueInputMask);
Vue.use(moment);

Vue.filter('money', function (value) {

    if(value == 'Erro') return value;

    let val = (value/1).toFixed(2).replace('.', ',');
    return 'R$ ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

});

Vue.filter('percentage', function (value) {

    if(value == 'Erro') return value;

    let val = (value/1).toFixed(2).replace('.', ',');
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '% ';

});

Vue.filter('date', function (value) {
    return moment(value).format("DD/MM/YYYY");
});

Vue.filter('datetime', function (value) {
    return moment(value).format("DD/MM/YYYY - HH:mm");
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//COMPONENT DE STOCKS
Vue.component('stocks-index-component', require('./components/painel/stocks/StocksComponent.vue').default);

//COMPONENT DE FORNECEDORES
Vue.component('providers-index-component', require('./components/painel/providers/ProvidersComponent.vue').default);

//COMPONENT DE COMPRAS
Vue.component('purchases-index-component', require('./components/painel/purchases/PurchasesComponent.vue').default); 

//COMPONENTS DE USUARIOS
Vue.component('users-index-component', require('./components/painel/users/UsersComponent.vue').default); 
Vue.component('users-show-component', require('./components/painel/users/ShowUserComponent.vue').default); 

//COMPONENT DE TIPO DE USUÁRIOS
Vue.component('user-type-index-component', require('./components/painel/user_types/UserTypeComponent.vue').default);

//COMPONENT DE OPÇÕES
Vue.component('options-index-component', require('./components/painel/options/OptionsComponent.vue').default);

//COMPONENT DE CATEGORIAS
Vue.component('categories-index-component', require('./components/painel/categories/CategoriesComponent.vue').default);

//COMPONENTS DE PRODUTOS
Vue.component('products-index-component', require('./components/painel/products/ProductsComponent.vue').default);
Vue.component('product-component', require('./components/painel/products/ProductComponent.vue').default);

//COMPONENT DE EMBALAGENS
Vue.component('boxes-index-component', require('./components/painel/boxes/BoxesComponent.vue').default);

// COMPONENT DE CUPONS
Vue.component('coupons-index-component', require('./components/painel/coupons/CouponsComponent.vue').default);

// COMPONENT DE CLIENTS
Vue.component('clients-index-component', require('./components/painel/clients/ClientsComponent.vue').default);
Vue.component('client-show-component', require('./components/painel/clients/ClientComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
