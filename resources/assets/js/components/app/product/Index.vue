<template>
    <!-- Main content -->
    <section class="content">
        <p class="text-muted f-s-17">
            You can set up all Categories and Products you want to keep an eye on and SpotLite will update the prices
            for you automatically. Simply add a Category name, then a Product name. Now all you have to do is copy and
            paste the Product Page URLs of the prices you want to track on the Product Page URL field within each
            Product section, as shown below.
        </p>
        <p class="text-muted m-b-20">
            Note: you can find the Product Page URLs on your competitors' website, usually on the product details page
            or where the pricing is located within their website.
        </p>

        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row m-b-20">
                    <div class="col-md-8">
                        <div class="text-muted f-w-bold l-h-30" v-if="user.subscription">
                            {{ subscriptionPlan.name }} Plan:
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="progress vertical-align-middle product-usage-progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped"
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
                    <div class="col-md-4 text-right">
                        <a href="#" class="btn btn-purple btn-flat" v-if="hasCategories">
                            <i class="fa fa-bell-o"></i>
                            &nbsp;
                            SET UP ALERT
                        </a>
                    </div>
                </div>
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <add-category @added-category="reloadCategories"></add-category>
                    </div>
                </div>
                <div class="row m-b-20" v-if="hasCategories">
                    <div class="col-sm-12 text-right">
                        <a href="#" class="text-muted btn-collapse-all" @click.prevent="toggleAllCategories"
                           v-text="shouldExpandAll ? 'Expand All' : 'Collapse All'"></a>
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
    </section>
</template>

<script>
    import addCategory from './AddCategory.vue';
    import singleCategory from './SingleCategory.vue';

    import {
        TOGGLE_ALL_CATEGORIES, LOAD_USER, SET_CATEGORY_SEARCH_PROMISE
    } from '../../../actions/action-types';

    export default {
        components: {
            addCategory,
            singleCategory
        },
        data() {
            return {
                categories: []
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
                let categoryPromise = setTimeout(()=>{
                    this.loadCategories();
                }, 1000);
                this.$store.dispatch(SET_CATEGORY_SEARCH_PROMISE, {
                    category_search_promise: categoryPromise
                });
            }
        },
        methods: {
            loadCategories: function () {
                axios.get('/category').then(response => {
                    if (response.data.status == true) {
                        this.categories = response.data.categories;
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            reloadCategories: function () {
                this.loadCategories();
                this.loadUser();
            },
            toggleAllCategories: function () {
                this.$store.dispatch(TOGGLE_ALL_CATEGORIES);
            },
            loadUser: function () {
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
            productUsagePercentage()
            {
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