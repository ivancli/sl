/**
 * Created by ivan.li on 2/23/2017.
 */

import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import loginUserStore from '../../loginUser';
import defaultStore from '../../default';
import subscriptionPlans from '../../subscription/subscriptionPlans';

import profile from './profile';
import display from './display';
import company from './company';

export default new Vuex.Store({
    modules: {
        loginUserStore,
        defaultStore,
        subscriptionPlans,
        profile,
        display,
        company,
        index: {
            state: {},
            mutations: {},
            actions: {},
            getters: {}
        }
    }
});