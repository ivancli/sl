require('../bootstrap');

import Vue from 'vue';

import auth from '../components/Auth.vue';
import register from '../components/auth/Register.vue';
import pricingTable from '../components/subscription/PricingTable.vue';
import store from '../stores/auth/register';

const sl = new Vue({
    el: '#sl',
    components: {
        auth,
        register,
        pricingTable
    },
    store
});
