require('../../../bootstrap');

import Vue from 'vue';

import defaultTemplate from '../../../components/Default.vue';
import defaultHeader from '../../../components/partials/Header.vue';
import defaultSidebar from '../../../components/partials/Sidebar.vue';
import defaultContent from '../../../components/partials/Content.vue';
import contentHeader from '../../../components/partials/content/ContentHeader.vue';
import contentBody from '../../../components/app/admin/user_activity_log/Index.vue';

import store from '../../../stores/app/admin/user_activity_log/index';

const sl = new Vue({
    el: '#sl',
    components: {
        defaultTemplate,
        defaultHeader,
        defaultSidebar,
        defaultContent,
        contentHeader,
        contentBody
    },
    store
});
