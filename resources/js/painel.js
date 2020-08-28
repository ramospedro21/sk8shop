/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

require('./bootstrap');

require('./helpers/cep');


const VueInputMask = require('vue-inputmask').default
Vue.use(VueInputMask)

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

//COMPONENT DE USUARIOS
Vue.component('users-index-component', require('./components/painel/users/UsersComponent.vue').default); 
Vue.component('users-show-component', require('./components/painel/users/ShowUserComponent.vue').default); 

//COMPONENT DE TIPO DE USUÁRIOS
Vue.component('user-type-index-component', require('./components/painel/user_types/UserTypeComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});