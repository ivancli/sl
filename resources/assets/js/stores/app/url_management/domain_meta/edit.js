/**
 * Created by Ivan on 5/03/2017.
 */

import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import defaultStore from '../../../default';

export default new Vuex.Store({
    modules: {
        defaultStore,
        index: {
            state: {
                addedDomainMetas: []
            },
            mutations: {
                /*TODO create mutation function*/
            },
            actions: {
                /*TODO create action function for mutation function*/
            },
            getters: {
                /*TODO create getter */
            }
        }
    }
});