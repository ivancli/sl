require('../bootstrap');

Vue.component('auth', require('../components/Auth.vue'));
Vue.component('forgot', require('../components/auth/Forgot.vue'));

const sl = new Vue({
    el: '#sl',
});
