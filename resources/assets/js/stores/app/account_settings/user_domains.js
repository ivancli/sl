/**
 * Created by Ivan on 28/05/2017.
 */
import Vue from 'vue';

import {
    LOAD_USER_DOMAINS
} from '../../../actions/mutation-types';


export default {
    state: {
        userDomains: []
    },
    mutations: {
        [LOAD_USER_DOMAINS] (state, params){
            axios.get('user/user-domain').then(response => {
                if (response.data.status === true) {
                    state.userDomains = response.data.userDomains;
                }
            }).catch(error => {
                console.info('error', error);
            });
        },
    },
    actions: {
        loadUserDomains({commit}, params){
            commit(LOAD_USER_DOMAINS, params);
        },
    },
    getters: {
        userDomains: state => state.userDomains
    }
}