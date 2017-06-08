<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row m-b-20" v-if="subscriptionIsValid">
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
                <div class="row m-b-20" v-else>
                    <div class="col-sm-12">
                        <div class="invalid-subscription-msg-container">
                            <table class="table" style="margin: 0; color: white;" v-if="subscriptionIsPastDue">
                                <tr>
                                    <td class="invalid-subscription-msg">
                                        <h3>OH NO! YOUR TRIAL HAS EXPIRED!</h3>
                                        <p>To re-activate your account and
                                            continue to use SpotLite, please add a payment method.</p>
                                    </td>
                                    <td class="shrink invalid-subscription-button">
                                        <a :href="updatePaymentLink" class="btn btn-primary btn-lg btn-flat">ADD PAYMENT METHOD</a>
                                    </td>
                                </tr>
                            </table>
                            <table class="table" v-if="subscriptionIsCancelled">
                                <tr>
                                    <td class="invalid-subscription-msg">
                                        <h3>OH NO! YOUR SUBSCRIPTION IS CANCELLED!</h3>
                                        <p>Please reactivate to continue using SpotLite.</p>
                                    </td>
                                    <td class="shrink invalid-subscription-button">
                                        <a href="#" class="btn btn-primary btn-lg btn-flat" @click.prevent="onClickReactivate">REACTIVATE</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row m-b-10">
                    <div class="col-sm-8">
                        <add-category @added-category="addedCategory" v-if="subscriptionIsValid"></add-category>
                    </div>
                    <div class="col-sm-4 text-right" v-if="hasCategories">
                        <div class="collapse-container">
                            <a href="#" class="text-muted btn-collapse-all" @click.prevent="toggleAllCategories" v-text="shouldExpandAll ? 'Expand All' : 'Collapse All'"></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <single-category v-for="single_category in categories" :current-category="single_category" @reload-category="updateCategory" @reload-categories="reloadCategories" @deleted-category="deletedCategory"></single-category>
                    </div>
                </div>
                <div class="row" v-if="isLoadingCategories">
                    <div class="col-sm-12 text-center">
                        <dotdotdot></dotdotdot>
                    </div>
                </div>
                <div class="row" v-if="!isLoadingCategories && !categoriesLessThanLength">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-default btn-flat" @click.prevent="loadCategories">LOAD MORE CATEGORIES</button>
                    </div>
                </div>
                <!--<div class="row">-->
                <!--<div class="col-sm-12" v-for="category in categories">-->
                <!--<div v-for="product in category.products">-->
                <!--{{product.id}}-->
                <!--</div>-->
                <!--</div>-->
                <!--</div>-->
            </div>
        </div>
        <loading v-if="isSearchingCategories || isReactivating"></loading>
        <create-password v-if="!hasSetPassword" @set-password="loadUser"></create-password>
        <welcome v-if="hasSetPassword && !hasSetSamples" @set-welcome="setWelcome"></welcome>
    </section>
</template>

<script>
    import Vue from 'vue';

    import createPassword from '../account_settings/popups/CreatePassword.vue';
    import welcome from '../account_settings/popups/Welcome.vue';

    import addCategory from './AddCategory.vue';
    import singleCategory from './SingleCategory.vue';
    import dotdotdot from '../../fragments/loading/DotDotDot.vue';
    import loading from '../../fragments/loading/Loading.vue';

    import {
        TOGGLE_ALL_CATEGORIES, LOAD_USER, SET_CATEGORY_SEARCH_PROMISE, CLEAR_CATEGORY_SEARCH_PROMISE, LOAD_USER_DOMAINS, LOAD_UPDATE_PAYMENT_LINK
    } from '../../../actions/action-types';

    export default {
        components: {
            createPassword,
            welcome,
            addCategory,
            singleCategory,
            dotdotdot,
            loading
        },
        data() {
            return {
                categories: [],
                isSearchingCategories: false,
                isReactivating: false,
                categoryLength: 5,
                isLoadingCategories: false,
                categoriesLessThanLength: false,
            }
        },
        mounted() {
            console.info('Index component is mounted');
            this.loadCategories();
            this.loadUserDomains();
            this.loadUpdatePaymentLink();
        },
        watch: {
            productSearchTerm(){
                if (this.categorySearchPromise !== null) {
                    clearTimeout(this.categorySearchPromise)
                }
                let categoryPromise = setTimeout(() => {
                    this.isSearchingCategories = true;
                    this.txtSearchProductReference.blur();
                    this.reloadCategories(() => {
                        this.isSearchingCategories = false;
                        this.txtSearchProductReference.focus();
                        this.$store.dispatch(CLEAR_CATEGORY_SEARCH_PROMISE);
                    });
                }, 1000);
                this.$store.dispatch(SET_CATEGORY_SEARCH_PROMISE, {
                    category_search_promise: categoryPromise
                });
            },
            user(val){
                if (val.hasOwnProperty('subscription')) {
                    this.loadUpdatePaymentLink();
                }
            }
        },
        methods: {
            loadCategories(callback) {
                this.isLoadingCategories = true;
                axios.get('/category', this.loadCategoriesRequestData).then(response => {
                    this.isLoadingCategories = false;
                    if (response.data.status === true) {
                        this.categories = this.categories.concat(response.data.categories);
                        /*TODO everytime when users add new categories, it's manually added to the array and it still yet to be loaded in load more*/
                        if (response.data.categories.length < this.categoryLength) {
                            this.categoriesLessThanLength = true;
                        }
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                }).catch(error => {
                    this.isLoadingCategories = false;
                    console.info(error.response);
                })
            },
            updateCategory(category){
                this.reloadCategory(category);
            },
            reloadCategory(category){
                axios.get(category.urls.show).then(response => {
                    if (response.data.status === true) {
                        this.setCategory(response.data.category);
                    }
                }).catch(error => {
                    console.info(error)
                })
            },
            setCategory(newCategory){
                let index = this.categories.findIndex(category => category.id === newCategory.id);
                Vue.set(this.categories, index, newCategory);
            },
            reloadCategories(callback) {
                this.categoriesLessThanLength = false;
                this.categories = [];
                this.loadCategories(callback);
                this.loadUser();
            },
            toggleAllCategories() {
                this.$store.dispatch(TOGGLE_ALL_CATEGORIES);
            },
            loadUser() {
                this.$store.dispatch(LOAD_USER);
            },
            loadUserDomains(){
                this.$store.dispatch(LOAD_USER_DOMAINS);
            },
            loadUpdatePaymentLink(){
                this.$store.dispatch(LOAD_UPDATE_PAYMENT_LINK);
            },
            setWelcome(){
                this.loadUser();
                this.loadCategories();
            },
            onClickReactivate(){
                this.isReactivating = true;
                if (this.apiSubscription.credit_card_id === null) {
                    window.location.href = this.updatePaymentLink;
                } else {
                    axios.post(this.subscription.urls.reactivate).then(response => {
                        if (response.data.status === true) {
                            this.isReactivating = false;
                            window.location.href = '/account-settings#manage-subscription';
                        }
                    }).catch(error => {
                        this.isReactivating = false;
                        if (error.response && error.response.status === 422 && error.response.data) {
                            this.errors = error.response.data;
                        }
                    });
                }
            },
            addedCategory(category){
                this.loadUser();
                if (this.categoriesLessThanLength === true) {
                    this.categories.push(category);
                }
            },
            deletedCategory(category){
                console.info("deleted-category", category);
                this.spliceCategory(category);
                this.loadUser();
            },
            spliceCategory(deletedCategory){
                let index = this.categories.findIndex(category => deletedCategory.id === category.id);
                console.info("index", index);
                this.categories.splice(index, 1);
            },
        },
        computed: {
            allCollapseStatus(){
                return this.$store.getters.categoriesCollapsed;
            },
            shouldExpandAll(){
                let shouldExpand = true;
                for (let categoryCollapseStatus in this.allCollapseStatus) {
                    if (this.allCollapseStatus.hasOwnProperty(categoryCollapseStatus) && this.allCollapseStatus[categoryCollapseStatus] === false) {
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
            hasSetPassword(){
                return this.user.set_password === 'y';
            },
            hasSetSamples(){
                return this.user.set_samples === 'y';
            },
            subscription(){
                if (typeof this.user.subscription !== 'undefined' && this.user.subscription !== null) {
                    return this.user.subscription;
                }
                return null;
            },
            apiSubscription(){
                if (this.subscription !== null) {
                    return this.subscription.apiSubscription;
                }
            },
            subscriptionIsValid(){
                if (this.subscription !== null) {
                    return this.subscription.isValid;
                }
                return true;
            },
            subscriptionIsPastDue(){
                if (this.subscription !== null) {
                    return this.subscription.isPastDue === true;
                }
                return false;
            },
            subscriptionIsCancelled()
            {
                if (this.subscription !== null) {
                    return this.subscription.isCancelled === true;
                }
                return false;
            },
            updatePaymentLink(){
                return this.$store.getters.updatePaymentLink;
            },
            numberOfProducts(){
                return this.user.numberOfProducts;
            },
            maxNumberOfProducts(){
                if (this.subscription !== null) {
                    if (this.subscription.subscriptionCriteria.product == 0) {
                        return null;
                    }
                    return this.subscription.subscriptionCriteria.product;
                }
                return null;
            },
            moreCategoriesToLoad(){
                return this.categories.length < this.user.numberOfCategories;
            },
            productUsagePercentage(){
                if (this.maxNumberOfProducts !== null) {
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
                        offset: this.categories.length,
                        length: this.categoryLength,
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

    .invalid-subscription-msg-container {
        background-color: #7ed0c0;
        padding: 15px;
    }

    .invalid-subscription-msg-container table {
        margin: 0;
        color: white;
    }

    .invalid-subscription-msg {
        border: none !important;
    }

    .invalid-subscription-msg h3 {
        margin-top: 0;
    }

    .invalid-subscription-msg p {
        font-size: 18px;
        margin-bottom: 0
    }

    .invalid-subscription-button {
        border: none !important;
        vertical-align: middle !important;
    }
</style>