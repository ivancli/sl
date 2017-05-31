/**
 * Created by ivan.li on 31/05/2017.
 */
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import loginUserStore from '../../loginUser';
import defaultStore from '../../default';
import userDomains from '../account_settings/user_domains';

export default new Vuex.Store({
    modules: {
        loginUserStore,
        defaultStore,
        userDomains,
    }
});