/**
 * Created by ivan.li on 2/20/2017.
 */

import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import defaultStore from '../../../default';
import loginUserStore from '../../../loginUser';

export default new Vuex.Store({
    modules: {
        loginUserStore,
        defaultStore,
        index: {
            state: {},
            mutations: {},
            actions: {},
            getters: {}
        }
    }
});