require('../bootstrap');

import Vue from 'vue';

import auth from '../components/Auth.vue';
import login from '../components/auth/Login.vue';

const sl = new Vue({
    el: '#sl',
    components: {
        auth, login
    }
});
