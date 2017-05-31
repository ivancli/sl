require('../bootstrap');

import Vue from 'vue';

import auth from '../components/Auth.vue';
import forgot from '../components/auth/Forgot.vue';

const sl = new Vue({
    el: '#sl',
    components: {
        auth, forgot
    }
});
