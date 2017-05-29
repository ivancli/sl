/**
 * Created by ivan.li on 3/3/2017.
 */
import Vue from 'vue';

import {
    SET_CATEGORY_COLLAPSE_STATUS,
    TOGGLE_COLLAPSE_CATEGORY,
    COLLAPSE_ALL_CATEGORIES,
    EXPAND_ALL_CATEGORIES,
    TOGGLE_ALL_CATEGORIES
} from '../../../actions/mutation-types';

export default{
    state: {
        categoriesCollapsed: {}
    },
    mutations: {
        [SET_CATEGORY_COLLAPSE_STATUS] (state, params){
            Vue.set(state.categoriesCollapsed, params.category_id, params.status)
        },
        [TOGGLE_COLLAPSE_CATEGORY] (state, params){
            if (typeof state.categoriesCollapsed[params.category_id] == 'undefined') {
                Vue.set(state.categoriesCollapsed, params.category_id, true)
            } else {
                Vue.set(state.categoriesCollapsed, params.category_id, !state.categoriesCollapsed[params.category_id]);
            }
        },
        [COLLAPSE_ALL_CATEGORIES] (state){
            for (let category_id in state.categoriesCollapsed) {
                if (state.categoriesCollapsed.hasOwnProperty(category_id)) {
                    state.categoriesCollapsed[category_id] = true;
                }
            }
        },
        [EXPAND_ALL_CATEGORIES] (state){
            for (let category_id in state.categoriesCollapsed) {
                if (state.categoriesCollapsed.hasOwnProperty(category_id)) {
                    state.categoriesCollapsed[category_id] = true;
                }
            }
        },
        [TOGGLE_ALL_CATEGORIES] (state){
            let shouldCollapse = false;
            for (let cid in state.categoriesCollapsed) {
                if (state.categoriesCollapsed.hasOwnProperty(cid) && state.categoriesCollapsed[cid] === false) {
                    shouldCollapse = true;
                    break;
                }
            }
            for (let category_id in state.categoriesCollapsed) {
                if (state.categoriesCollapsed.hasOwnProperty(category_id)) {
                    Vue.set(state.categoriesCollapsed, category_id, shouldCollapse)
                }
            }
        }
    },
    actions: {
        setCategoryCollapseStatus({commit, state}, params){
            commit(SET_CATEGORY_COLLAPSE_STATUS, params);
        },
        toggleCollapseCategory({commit, state}, params){
            commit(TOGGLE_COLLAPSE_CATEGORY, params);
        },
        collapseAllCategories({commit, state}, params){
            commit(COLLAPSE_ALL_CATEGORIES, params);
        },
        expandAllCategories({commit, state}, params){
            commit(EXPAND_ALL_CATEGORIES, params);
        },
        toggleAllCategories({commit, state}, params){
            commit(TOGGLE_ALL_CATEGORIES, params);
        },
    },
    getters: {
        categoriesCollapsed: state => state.categoriesCollapsed
    }
}