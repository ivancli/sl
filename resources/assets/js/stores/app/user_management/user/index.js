/**
 * Created by ivan.li on 2/14/2017.
 */
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import defaultStore from '../../../default';

export default new Vuex.Store({
    modules: {
        defaultStore,
        index: {
            state: {
                categoriesCollapsed: {}
            },
            mutations: {},
            actions: {},
            getters: {}
        }
    }
});