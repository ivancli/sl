<template>
    <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
            <input type="text" class="form-control general-search-input" placeholder="SEARCH" @input.prevent="setProductSearchTerm" v-model="productSearchTerm">
        </div>
    </form>
</template>

<script>
    import {
        SET_PRODUCT_SEARCH_TERM
    } from '../../../actions/action-types';

    export default {
        data(){
            return {
                productSearchTerm: '',
            }
        },
        mounted() {
            console.info('Filter component mounted.')
        },
        methods: {
            setProductSearchTerm(){
                this.$store.dispatch(SET_PRODUCT_SEARCH_TERM, {
                    product_search_term: this.productSearchTerm
                });
            }
        }
    }
</script>

<style>
    .general-search-input {
        color: #fff !important;
        background-color: #98dacd !important;
    }

    .general-search-input::-webkit-input-placeholder { /* WebKit, Blink, Edge */
        color: #fff !important;
    }

    .general-search-input:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color: #fff !important;
        opacity: 1 !important;
    }

    .general-search-input::-moz-placeholder { /* Mozilla Firefox 19+ */
        color: #fff !important;
        opacity: 1 !important;
    }

    .general-search-input:-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #fff !important;
    }
</style>