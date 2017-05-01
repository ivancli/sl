/**
 * Created by ivan.li on 5/1/2017.
 */
import Vue from 'vue';

import {
    SET_PRODUCT_SEARCH_TERM, SET_CATEGORY_SEARCH_PROMISE, CLEAR_CATEGORY_SEARCH_PROMISE, SET_PRODUCT_SEARCH_PROMISE, CLEAR_PRODUCT_SEARCH_PROMISE
} from '../../../actions/mutation-types';

export default{
    state: {
        productSearchTerm: '',
        categorySearchPromise: null,
        productSearchPromise: [],
    },
    mutations: {
        [SET_PRODUCT_SEARCH_TERM] (state, params){
            state.productSearchTerm = params.product_search_term;
        },
        [SET_CATEGORY_SEARCH_PROMISE] (state, params){
            state.categorySearchPromise = params.category_search_promise;
        },
        [CLEAR_CATEGORY_SEARCH_PROMISE] (state, params){
            state.categorySearchPromise = null;
        },
        [SET_PRODUCT_SEARCH_PROMISE] (state, params){
            Vue.set(state.productSearchPromise, params.product_id, params.productSearchPromise);
        },
        [CLEAR_PRODUCT_SEARCH_PROMISE] (state, params){
            Vue.set(state.productSearchPromise, params.product_id, null);
        },
    },
    actions: {
        setProductSearchTerm({commit, state}, params){
            commit(SET_PRODUCT_SEARCH_TERM, params);
        },
        setCategorySearchPromise({commit, state}, params){
            commit(SET_CATEGORY_SEARCH_PROMISE, params);
        },
        clearCategorySearchPromise({commit, state}, params){
            commit(CLEAR_CATEGORY_SEARCH_PROMISE, params);
        },
        setProductSearchPromise({commit, state}, params){
            commit(SET_PRODUCT_SEARCH_PROMISE, params);
        },
        clearProductSearchPromise({commit, state}, params){
            commit(CLEAR_PRODUCT_SEARCH_PROMISE, params);
        },
    },
    getters: {
        productSearchTerm: state => state.productSearchTerm,
        categorySearchPromise: state => state.categorySearchPromise,
        productSearchPromise: state => state.productSearchPromise,
    }
}