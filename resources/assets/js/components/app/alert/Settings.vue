<template>
    <div class="p-l-20">
        <form>
            <div class="row">
                <div class="col-sm-12">
                    <p>To receive price change email alerts, choose from the following options:</p>
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
                                    <single-category v-for="category in categories" :current-category="category" @option-changed="updateAlerts"></single-category>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary">CONFIRM</button>
                    <button class="btn btn-default">CANCEL</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import singleCategory from './settings/SingleCategory.vue';

    export default{
        components:{
            singleCategory
        },
        data(){
            return {
                basicActive: false,
                advancedActive: false,
                categories: [],
                alerts: {},
            }
        },
        mounted(){
            console.info('Settings component mounted.');
            this.loadCategoriesAndProducts();
        },
        watch: {
            basicActive(val){
                if (val == true) {
                    this.advancedActive = false;
                }
            },
            advancedActive(val){
                if (val == true) {
                    this.basicActive = false;
                }
            }
        },
        methods: {
            loadCategoriesAndProducts(callback){
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
            updateAlerts(alerts){

            }
        },
        computed: {
            basicActiveClass(){
                if (this.basicActive == true) {
                    return 'active'
                }
                return '';
            },
            advancedActiveClass(){
                if (this.advancedActive == true) {
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
            }
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