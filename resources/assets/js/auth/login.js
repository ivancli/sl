require('../bootstrap');

const Auth = Vue.component('auth', require('../components/Auth.vue'));
const Login = Vue.component('login', require('../components/auth/Login.vue'));

const sl = new Vue({
    el: '#sl',
});
