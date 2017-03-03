/**
 * Created by ivan.li on 2/14/2017.
 */
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import loginUserStore from '../../loginUser';
import defaultStore from '../../default';
import collapse from './collapse';

export default new Vuex.Store({
    modules: {
        loginUserStore,
        defaultStore,
        collapse
    }
});