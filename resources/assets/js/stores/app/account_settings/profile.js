import Vue from 'vue';

import {
    SET_PROFILE
} from '../../../actions/mutation-types';


export default {
    state: {
        profile: {
            title: null,
            first_name: null,
            last_name: null,
            email: null
        }
    },
    mutations: {
        [SET_PROFILE] (state, params){
            Vue.set(state.profile, 'title', params.profile.title);
            Vue.set(state.profile, 'first_name', params.profile.first_name);
            Vue.set(state.profile, 'last_name', params.profile.last_name);
            Vue.set(state.profile, 'email', params.profile.email);
        },
    },
    actions: {
        setProfile({commit}, params){
            commit(SET_PROFILE, params);
        },
    },
    getters: {
        profile: state => state.profile
    }
}