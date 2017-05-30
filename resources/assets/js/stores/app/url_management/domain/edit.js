/**
 * Created by Ivan on 5/03/2017.
 */

import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import loginUserStore from '../../../loginUser';
import defaultStore from '../../../default';

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