require('../bootstrap');

import Vue from 'vue';

import auth from '../components/Auth.vue';
import reset from '../components/auth/Reset.vue';

const sl = new Vue({
    el: '#sl',
    components: {
        auth, reset
    }
});
