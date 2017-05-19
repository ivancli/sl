<template>
    <div class="p-l-20">
        <form>
            <div class="row">
                <div class="col-sm-12">
                    <p>To receive price change email alerts, choose from the following options:</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-3">
                    <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                        <li v-for="error in errors">
                            <div v-if="error.constructor != Array" v-text="error"></div>
                            <div v-else v-for="message in error" v-text="message"></div>
                        </li>
                    </ul>
                    <ul class="p-b-10 p-l-20 success-container" v-if="successMsg != ''">
                        <li v-text="successMsg"></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <ul class="text-muted alert-options-list">
                        <li class="m-b-20" :class="basicActiveClass">
                            <div class="checkbox basic-checkbox">
                                <label>
                                    <input type="checkbox" v-model="basicActive">
                                    &nbsp;
                                    <i class="alert-collapse-indicator fa fa-play"></i>
                                    &nbsp;
                                    Basic Alerts - a single alert type across all Categories and Products
                                </label>
                            </div>
                            <div class="basic-options p-l-50" v-if="basicActive">
                                Send alert when
                                &nbsp;
                                <select class="input-sm">
                                    <option value="">-- select alert type --</option>
                                    <option value="my_price">my price was beaten</option>
                                    <option value="price_change">price changes</option>
                                </select>
                                &nbsp;
                                in all categories.
                            </div>
                        </li>
                        <li class="m-b-20" :class="advancedActiveClass">
                            <div class="checkbox advanced-checkbox">
                                <label>
                                    <input type="checkbox" v-model="advancedActive">
                                    &nbsp;
                                    <i class="alert-collapse-indicator fa fa-play"></i>
                                    &nbsp;
                                    Advanced Alerts - individual alerts for Categories and Products
                                    &nbsp;
                                    <i class="fa fa-info-circle"></i>
                                </label>
                            </div>

                            <div class="advanced-options p-l-70" v-if="advancedActive">
                                <ul class="category-options-list">
                                    <single-category v-for="category in categories" :current-category="category" @category-alert-updated="updateCategoryAlerts" @product-alerts-updated="updateProductAlerts"></single-category>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary btn-flat" @click.prevent="updateAlerts">CONFIRM</button>
                    <button class="btn btn-default btn-flat">CANCEL</button>
                </div>
            </div>
        </form>
        <loading v-if="isUpdatingAlerts"></loading>
    </div>
</template>

<script>
    import singleCategory from './settings/SingleCategory.vue';

    import loading from '../../fragments/loading/Loading.vue';

    export default{
        components: {
            singleCategory,
            loading,
        },
        data(){
            return {
                basicActive: false,
                advancedActive: false,
                categories: [],
                categoryAlerts: {},
                productAlerts: {},
                isUpdatingAlerts: false,
                isLoadingCategoriesAndProducts: false,
                errors: [],
                successMsg: '',
            }
        },
        mounted(){
            console.info('Settings component mounted.');
            this.loadCategoriesAndProducts();
        },
        watch: {
            basicActive(val){
                if (val === true) {
                    this.advancedActive = false;
                }
            },
            advancedActive(val){
                if (val === true) {
                    this.basicActive = false;
                }
            }
        },
        methods: {
            loadCategoriesAndProducts(callback){
                this.isLoadingCategoriesAndProducts = true;
                axios.get('/category', this.loadCategoriesRequestData).then(response => {
                    this.isLoadingCategoriesAndProducts = false;
                    if (response.data.status === true) {
                        this.categories = response.data.categories;
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                }).catch(error => {
                    this.isLoadingCategoriesAndProducts = false;
                    console.info(error.response);
                })
            },
            updateCategoryAlerts(categoryAlert){
                if (categoryAlert.is_selected === true && categoryAlert.type !== null && categoryAlert.type.length > 0) {
                    this.categoryAlerts[categoryAlert.category_id] = categoryAlert;
                } else {
                    delete this.categoryAlerts[categoryAlert.category_id];
                }
            },
            updateProductAlerts(productAlerts){
                for (let product_id in productAlerts) {
                    if (productAlerts.hasOwnProperty(product_id)) {
                        let productAlert = productAlerts[product_id];
                        if (productAlert.is_selected === true && productAlert.type !== null && productAlert.type.length > 0) {
                            this.productAlerts[productAlert.product_id] = productAlert;
                        } else {
                            delete this.productAlerts[productAlert.product_id];
                        }
                    }
                }
            },
            updateAlerts(){
                this.clearSuccessMsg();
                this.clearErrorMsg();
                this.isUpdatingAlerts = true;
                axios.post('/alert', this.updateAlertsRequestData).then(response => {
                    this.isUpdatingAlerts = false;
                    if (response.data.status === true) {
                        this.isUpdatingAlerts = false;
                        this.setSuccessMsg();
                    }
                }).catch(error => {
                    this.isUpdatingAlerts = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            setSuccessMsg(){
                this.successMsg = "Alert settings have been updated.";
            },
            clearSuccessMsg(){
                this.successMsg = '';
            },
            clearErrorMsg(){
                this.errors = [];
            },
        },
        computed: {
            basicActiveClass(){
                if (this.basicActive === true) {
                    return 'active'
                }
                return '';
            },
            advancedActiveClass(){
                if (this.advancedActive === true) {
                    return 'active'
                }
                return '';
            },
            loadCategoriesRequestData(){
                return {
                    params: {
                        'with': 'products',
                    }
                }
            },
            updateAlertsRequestData(){
                return {
                    product_alerts: this.productAlerts,
                    category_alerts: this.categoryAlerts,
                };
            },
        }
    }
</script>

<style>
    .alert-options-list, .category-options-list {
        list-style: none;
        padding-left: 0;
    }

    .alert-collapse-indicator {
        transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        transition: transform 550ms ease;
        -moz-transition: -moz-transform 550ms ease;
        -ms-transition: -ms-transform 550ms ease;
        -o-transition: -o-transform 550ms ease;
        -webkit-transition: -webkit-transform 550ms ease;
    }

    li.active .alert-collapse-indicator {
        transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
        transition: transform 550ms ease;
        -moz-transition: -moz-transform 550ms ease;
        -ms-transition: -ms-transform 550ms ease;
        -o-transition: -o-transform 550ms ease;
        -webkit-transition: -webkit-transform 550ms ease;
        color: #696969;
    }
</style>