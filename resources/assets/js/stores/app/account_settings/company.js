/**
 * Created by Ivan on 11/05/2017.
 */
import Vue from 'vue';

import {
    SET_COMPANY
} from '../../../actions/mutation-types';


export default {
    state: {
        company: {
            industry: null,
            company_type: null,
            company_url: null,
            ebay_username: null,
        }
    },
    mutations: {
        [SET_COMPANY] (state, params){
            Vue.set(state.company, 'industry', params.company.industry);
            Vue.set(state.company, 'company_type', params.company.company_type);
            Vue.set(state.company, 'company_url', params.company.company_url);
            Vue.set(state.company, 'ebay_username', params.company.ebay_username);
        },
    },
    actions: {
        setCompany({commit}, params){
            commit(SET_COMPANY, params);
        },
    },
    getters: {
        company: state => state.company
    }
}