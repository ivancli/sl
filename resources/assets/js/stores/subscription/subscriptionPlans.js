/**
 * Created by ivan.li on 2/28/2017.
 */

import {
    SET_SUBSCRIPTION_PLAN_ID
} from '../../actions/mutation-types';

export default {
    state: {
        selectedSubscriptionPlanId: null
    },
    mutations: {
        [SET_SUBSCRIPTION_PLAN_ID] (state, params){
            state.selectedSubscriptionPlanId = params.selectedSubscriptionPlanId;
        }
    },
    actions: {
        setSubscriptionPlanId ({commit, state}, params){
            commit(SET_SUBSCRIPTION_PLAN_ID, params);
        },
    },
    getters: {
        selectedSubscriptionPlanId: state => state.selectedSubscriptionPlanId
    }
}