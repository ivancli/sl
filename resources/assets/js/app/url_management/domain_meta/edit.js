require('../../../bootstrap');

import Vue from 'vue';

import defaultTemplate from '../../../components/Default.vue';
import defaultHeader from '../../../components/partials/Header.vue';
import defaultContent from '../../../components/partials/Content.vue';
import contentHeader from '../../../components/partials/content/ContentHeader.vue';
import contentBody from '../../../components/app/url_management/domain_meta/Edit.vue';

import store from '../../../stores/app/url_management/domain_meta/edit';

const sl = new Vue({
    el: '#sl',
    components: {
        defaultTemplate,
        defaultHeader,
        defaultContent,
        contentHeader,
        contentBody,
    },
    store,
});
