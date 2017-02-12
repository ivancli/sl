/**
 * Created by Ivan on 11/02/2017.
 */
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import {
    TOGGLE_SIDEBAR, OPEN_SIDEBAR, COLLAPSE_SIDEBAR
} from '../actions/mutation-types';


export default new Vuex.Store({
    state: {
        sidebarOpened: true
    },
    mutations: {
        [TOGGLE_SIDEBAR] (state){
            state.sidebarOpened = !state.sidebarOpened;
        },
        [OPEN_SIDEBAR] (state){
            state.sidebarOpened = true;
        },
        [COLLAPSE_SIDEBAR] (state){
            state.sidebarOpened = false;
        }
    },
});