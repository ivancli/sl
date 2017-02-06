/**
 * Created by Ivan on 5/02/2017.
 */

import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import {
    SET_SUBSCRIPTION_PLAN_ID,
} from '../../actions/mutation-types';


export default new Vuex.Store({
    state: {
        selectedSubscriptionPlanId: null
    },
    mutations: {
        [SET_SUBSCRIPTION_PLAN_ID] (state, selectedSubscriptionPlanId){
            state.selectedSubscriptionPlanId = selectedSubscriptionPlanId;
        }
    },
});