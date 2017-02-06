require('../bootstrap');

import store from '../stores/auth/register';

Vue.component('auth', require('../components/Auth.vue'));
Vue.component('register', require('../components/auth/Register.vue'));
Vue.component('pricing-table', require('../components/subscription/PricingTable.vue'));

const sl = new Vue({
    el: '#sl',
    store,
});
