<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row m-b-20">
                    <div class="col-md-12">
                        <div class="text-muted f-w-bold l-h-30" v-if="user.subscription">
                            {{ subscriptionPlan.name }} Plan:
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="progress vertical-align-middle product-usage-progress">
                                <div class="progress-bar progress-bar-striped" :class="progressBarClass"
                                     role="progressbar" :aria-valuenow="productUsagePercentage" aria-valuemin="0"
                                     aria-valuemax="100" :style="{width: productUsagePercentage + '%'}">
                                </div>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            {{ numberOfProducts }}&nbsp;/&nbsp; {{ maxNumberOfProducts }}
                            &nbsp;
                            products
                        </div>
                    </div>
                </div>
                <div class="row m-b-10">
                    <div class="col-sm-8">
                        <add-category @added-category="reloadCategories"></add-category>
                    </div>
                    <div class="col-sm-4 text-right" v-if="hasCategories">
                        <div class="collapse-container">
                            <a href="#" class="text-muted btn-collapse-all" @click.prevent="toggleAllCategories" v-text="shouldExpandAll ? 'Expand All' : 'Collapse All'"></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <single-category v-for="single_category in categories" :current-category="single_category"
                                         @reload-categories="reloadCategories"></single-category>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12" v-for="category in categories">
                        <div v-for="product in category.products">
                            {{product.id}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="isSearchingCategories"></loading>
    </section>
</template>

<script>
    import addCategory from './AddCategory.vue';
    import singleCategory from './SingleCategory.vue';
    import loading from '../../fragments/loading/Loading.vue';

    import {
        TOGGLE_ALL_CATEGORIES, LOAD_USER, SET_CATEGORY_SEARCH_PROMISE, CLEAR_CATEGORY_SEARCH_PROMISE
    } from '../../../actions/action-types';

    export default {
        components: {
            addCategory,
            singleCategory,
            loading
        },
        data() {
            return {
                categories: [],
                isSearchingCategories: false
            }
        },
        mounted() {
            console.info('Index component is mounted');
            this.loadCategories();
        },
        watch: {
            productSearchTerm(){
                if (this.categorySearchPromise != null) {
                    clearTimeout(this.categorySearchPromise)
                }
                let categoryPromise = setTimeout(() => {
                    this.isSearchingCategories = true;
                    this.txtSearchProductReference.blur();
                    this.loadCategories(() => {
                        this.isSearchingCategories = false;
                        this.txtSearchProductReference.focus();
                        this.$store.dispatch(CLEAR_CATEGORY_SEARCH_PROMISE);
                    });
                }, 1000);
                this.$store.dispatch(SET_CATEGORY_SEARCH_PROMISE, {
                    category_search_promise: categoryPromise
                });
            }
        },
        methods: {
            loadCategories(callback) {
                axios.get('/category', this.loadCategoriesRequestData).then(response => {
                    if (response.data.status == true) {
                        this.categories = response.data.categories;
                        if (typeof callback == 'function') {
                            callback();
                        }
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            reloadCategories() {
                this.loadCategories();
                this.loadUser();
            },
            toggleAllCategories() {
                this.$store.dispatch(TOGGLE_ALL_CATEGORIES);
            },
            loadUser() {
                this.$store.dispatch(LOAD_USER);
            },
        },
        computed: {
            allCollapseStatus(){
                return this.$store.getters.categoriesCollapsed;
            },
            shouldExpandAll(){
                let shouldExpand = true;
                for (let categoryCollapseStatus in this.allCollapseStatus) {
                    if (this.allCollapseStatus.hasOwnProperty(categoryCollapseStatus) && this.allCollapseStatus[categoryCollapseStatus] == false) {
                        shouldExpand = false;
                        break;
                    }
                }
                return shouldExpand;
            },
            hasCategories(){
                return this.categories.length > 0;
            },
            user(){
                return this.$store.getters.user
            },
            numberOfProducts(){
                return this.user.numberOfProducts;
            },
            maxNumberOfProducts(){
                if (this.user.hasOwnProperty('subscription')) {
                    if (this.user.subscription.subscriptionCriteria.product == 0) {
                        return null;
                    }
                    return this.user.subscription.subscriptionCriteria.product;
                }
                return null;
            },
            productUsagePercentage(){
                if (this.maxNumberOfProducts != null) {
                    return this.numberOfProducts / this.maxNumberOfProducts * 100;
                }
                return 0;
            },
            subscriptionPlan(){
                if (this.user.hasOwnProperty('subscription')) {
                    return this.user.subscription.subscriptionPlan;
                }
                return null;
            },
            productSearchTerm(){
                return this.$store.getters.productSearchTerm;
            },
            categorySearchPromise(){
                return this.$store.getters.categorySearchPromise;
            },
            loadCategoriesRequestData(){
                return {
                    params: {
                        key: this.$store.getters.productSearchTerm,
                    }
                }
            },
            txtSearchProductReference(){
                return this.$store.getters.refTxtSearchProduct;
            },
            progressBarClass(){
                if (this.productUsagePercentage < 50) {
                    return 'progress-bar-success';
                } else if (this.productUsagePercentage < 90) {
                    return 'progress-bar-warning';
                } else {
                    return 'progress-bar-danger';
                }
            }
        }
    }
</script>

<style>
    .btn-purple {
        background-color: #A892C3;
        border-color: #ccb8e4;
        color: #fff;
    }

    .btn-purple:hover, .btn-purple:active, .btn-purple.hover {
        background-color: #8b74a9;
        border-color: #9c83bf;
        color: #fff;
    }

    .collapse-container {
        padding-top: 30px;
    }

    .btn-collapse-all {
        font-size: 12px;
    }

    .product-usage-progress {
        width: 300px;
        display: inline-block;
        margin-bottom: 0;
        background-color: #dedede;
        border-radius: 10px;
        height: 15px;
    }
</style>