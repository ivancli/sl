/**
 * Created by Ivan on 2/03/2017.
 */

import {
    LOAD_USER, SET_USER, SET_USER_ATTRIBUTE
} from '../actions/mutation-types';


export default {
    state: {
        user: user
    },
    mutations: {
        [LOAD_USER] (state){
            axios.get('/user/profile/' + user.id).then(response => {
                if (response.data.status == true) {
                    state.user = response.data.user;
                }
            })
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
        setUser({commit}){
            commit(SET_USER);
        },
        setUserAttribute({commit, state}, params){
            commit(SET_USER_ATTRIBUTE, params);
        }
    },
    getters: {
        user: state => state.user
    }
}