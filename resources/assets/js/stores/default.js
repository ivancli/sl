/**
 * Created by Ivan on 11/02/2017.
 */

import {
    TOGGLE_SIDEBAR, OPEN_SIDEBAR, COLLAPSE_SIDEBAR
} from '../actions/mutation-types';

export default {
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
    actions: {
        toggleSidebar ({commit}){
            commit(TOGGLE_SIDEBAR);
        },
        openSidebar ({commit}){
            commit(OPEN_SIDEBAR);
        },
        collapseSidebar ({commit}){
            commit(COLLAPSE_SIDEBAR);
        }
    },
    getters: {
        sidebarOpened: state => state.sidebarOpened
    }
}