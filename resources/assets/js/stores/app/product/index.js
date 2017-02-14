/**
 * Created by ivan.li on 2/14/2017.
 */
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import {
    LOAD_CATEGORIES, LOAD_PRODUCTS, LOAD_SITES
} from '../../../actions/mutation-types';


export default new Vuex.Store({
    state: {
        categories: []
    },
    mutations: {
        [LOAD_CATEGORIES] (state){
            axios.get('/category').then(response=> {
                if (response.data.status == true) {
                    state.categories = response.data.categories;
                }
            }).catch(error=> {
                console.info(error.response);
            })
        },
        [LOAD_PRODUCTS] (state, category_id){
            var requestData = {};
            requestData.category_id = category_id;
            axios.get('/product', {params: requestData}).then(response=> {
                if (response.data.status == true) {
                    state.categories.forEach(function (element, index, array) {
                        if (element.id == category_id) {
                            state.categories[index].products = response.data.products;
                        }
                    })
                }
            }).catch(error=> {
                console.info(error.response);
            })
        },
        [LOAD_SITES] (state){
            // axios.get('/category').then(response=> {
            //     if (response.data.status == true) {
            //         state.categories = response.data.categories;
            //     }
            // }).catch(error=> {
            //     console.info(error.response);
            // })
        }
    },
});