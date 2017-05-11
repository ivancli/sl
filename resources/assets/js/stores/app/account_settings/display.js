/**
 * Created by Ivan on 11/05/2017.
 */
import Vue from 'vue';

import {
    SET_DISPLAY
} from '../../../actions/mutation-types';


export default {
    state: {
        display: {
            DATE_FORMAT: null,
            TIME_FORMAT: null,
        }
    },
    mutations: {
        [SET_DISPLAY] (state, params){
            Vue.set(state.display, 'DATE_FORMAT', params.display.date_format);
            Vue.set(state.display, 'TIME_FORMAT', params.display.time_format);
        },
    },
    actions: {
        setDisplay({commit}, params){
            commit(SET_DISPLAY, params);
        },
    },
    getters: {
        display: state => state.display
    }
}