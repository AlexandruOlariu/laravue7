/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

var Vue=require('vue');
window.Bus = new Vue();
Vue.use(require('vue-resource'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('pricing',require('./components/Pricing.vue').default);
Vue.component('messenger',require('./components/messenger.vue').default);
Vue.component('bpmn_ds',require('./components/bpmn_ds.vue').default);
Vue.component('bpmn_table',require('./components/bpmn_table.vue').default);
Vue.component('bpmn_insert',require('./components/bpmn_insert.vue').default);
Vue.http.headers.common['X-CSRF-TOKEN']=$('meta[name="csrf-token"]').attr('content');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vuex from 'vuex'

Vue.use(Vuex)

import Auth from '@okta/okta-vue'

Vue.use(Auth, {
    issuer: process.env.MIX_APP_OKTA_CLIENT_URL+'/oauth2/default',
    client_id: process.env.MIX_APP_OKTA_CLIENT_ID,
    redirect_uri: process.env.MIX_APP_URL+'/implicit/callback',
    scope: 'openid profile email'
})
const app = new Vue({
    el: '#app',

});
