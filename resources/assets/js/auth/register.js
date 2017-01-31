require('../bootstrap');

Vue.component('auth', require('../components/Auth.vue'));
Vue.component('register', require('../components/auth/Register.vue'));

const sl = new Vue({
    el: '#sl',
});
