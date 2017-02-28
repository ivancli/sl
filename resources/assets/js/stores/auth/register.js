/**
 * Created by Ivan on 5/02/2017.
 */
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import subscriptionPlans from '../subscription/subscriptionPlans';

export default new Vuex.Store({
    modules: {
        subscriptionPlans,
        index: {
            state: {},
            mutations: {},
            actions: {},
            getters: {}
        }
    }
});