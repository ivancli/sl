/**
 * Created by Ivan on 20/02/2017.
 */

import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import defaultStore from '../../../default';

export default new Vuex.Store({
    modules: {
        defaultStore,
        index: {
            state: {},
            mutations: {},
            actions: {},
            getters: {}
        }
    }
});