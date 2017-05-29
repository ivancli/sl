/**
 * Created by Ivan on 2/03/2017.
 */
import Vue from 'vue';

import {
    LOAD_USER, SET_USER, SET_USER_ATTRIBUTE, LOAD_UPDATE_PAYMENT_LINK
} from '../actions/mutation-types';


export default {
    state: {
        user: user,
        updatePaymentLink: null,
    },
    mutations: {
        [LOAD_USER] (state){
            axios.get('/user/profile/' + user.id).then(response => {
                if (response.data.status === true) {
                    state.user = response.data.user;
                }
            })
        },
        [LOAD_UPDATE_PAYMENT_LINK](state){
            if (typeof state.user.subscription !== 'undefined' && state.user.subscription !== null) {
                axios.get(state.user.subscription.urls.edit).then(response => {
                    if (response.data.status === true) {
                        state.updatePaymentLink = response.data.link;
                    }
                })
            }
        },
        [SET_USER] (state){

        },
        [SET_USER_ATTRIBUTE](state){

        }
    },
    actions: {
        loadUser({commit}){
            commit(LOAD_USER);
        },
        loadUpdatePaymentLink({commit}){
            commit(LOAD_UPDATE_PAYMENT_LINK);
        },
        setUser({commit}){
            commit(SET_USER);
        },
        setUserAttribute({commit, state}, params){
            commit(SET_USER_ATTRIBUTE, params);
        }
    },
    getters: {
        user: state => state.user,
        updatePaymentLink: state => state.updatePaymentLink,
    }
}