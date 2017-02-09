require('../../bootstrap');

Vue.component('default-template', require('../../components/Default.vue'));
Vue.component('default-header', require('../../components/partials/Header.vue'));
Vue.component('default-sidebar', require('../../components/partials/Sidebar.vue'));
Vue.component('default-content', require('../../components/partials/Content.vue'));
Vue.component('content-header', require('../../components/partials/content/ContentHeader.vue'));

Vue.component('content-body', require('../../components/app/product/Index.vue'));

const sl = new Vue({
    el: '#sl',
});
