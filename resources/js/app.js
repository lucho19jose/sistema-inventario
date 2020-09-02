/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import axios from 'axios'
import toastr from 'toastr'
import 'toastr/build/toastr.css'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('welcome', require('./components/Welcome.vue').default);
Vue.component('category', require('./components/Category.vue').default);
Vue.component('product', require('./components/Product.vue').default);
Vue.component('provider', require('./components/Provider.vue').default);
Vue.component('staff', require('./components/Staff.vue').default);
Vue.component('inputproduct', require('./components/InputProduct.vue').default);
Vue.component('branch', require('./components/Branch.vue').default);
Vue.component('outputproduct', require('./components/OutputProduct.vue').default);
Vue.component('inputreport', require('./components/InputReport.vue').default);
Vue.component('outputreport', require('./components/OutputReport.vue').default);
Vue.component('stockreport', require('./components/StockReport.vue').default);
Vue.component('user', require('./components/User.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    data: {
    	menu: 0,
    }
});
