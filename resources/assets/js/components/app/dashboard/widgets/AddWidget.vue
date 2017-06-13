<template>

    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                Chart Characteristics
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                            <li v-for="(error,index) in errors">
                                <div v-if="error.constructor != Array" v-text="error"></div>
                                <div v-else v-for="message in error">
                                    <div v-if="index == 'id'">
                                        please select a {{ type }}
                                    </div>
                                    <div v-else v-text="message"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal form-sl-horizontal" @submit.prevent="addNewWidget">
                            <div class="form-group required">
                                <label for="sel-chart-type" class="control-label col-md-4">
                                    Chart type
                                </label>
                                <div class="col-md-8">
                                    <select id="sel-chart-type" class="form-control" v-model="type" @change.prevent="onChangeType">
                                        <option value="category">Category</option>
                                        <option value="product">Product</option>
                                        <option value="site">Product Page URL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label for="sel-category" class="control-label col-md-4">
                                    Category
                                </label>
                                <div class="col-md-8">
                                    <select id="sel-category" class="form-control" v-model="selectedCategory" @change.prevent="onCategoryChange">
                                        <option value="">-- select category --</option>
                                        <option v-for="category in categories" :value="category.id">{{ category.category_name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required" v-if="showProductControl">
                                <label for="sel-product" class="control-label col-md-4">
                                    Product
                                </label>
                                <div class="col-md-8">
                                    <select id="sel-product" class="form-control" v-model="selectedProduct" @change.prevent="onProductChange">
                                        <option value="">-- select product --</option>
                                        <option v-for="product in products" :value="product.id">{{ product.product_name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required" v-if="showSiteControl">
                                <label for="sel-site" class="control-label col-md-4">
                                    Product Page URL
                                </label>
                                <div class="col-md-8">
                                    <select id="sel-site" class="form-control" v-model="selectedSite">
                                        <option value="">-- select product page URL --</option>
                                        <option v-for="site in sites" :value="site.id">{{ displayName(site) }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label for="sel-timespan" class="control-label col-md-4">
                                    Timespan
                                </label>
                                <div class="col-md-8">
                                    <select id="sel-timespan" class="form-control" v-model="timespan">
                                        <option value="this_week">This week</option>
                                        <option value="last_week">Last week</option>
                                        <option value="last_7_days">Last 7 days</option>
                                        <option value="this_month">This month</option>
                                        <option value="last_month">Last month</option>
                                        <option value="last_30_days">Last 30 days</option>
                                        <option value="this_quarter">This quarter</option>
                                        <option value="last_quarter">Last quarter</option>
                                        <option value="last_90_days">Last 90 days</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label for="sel-resolution" class="control-label col-md-4">
                                    Period Resolution
                                </label>
                                <div class="col-md-8">
                                    <select id="sel-resolution" class="form-control" v-model="resolution">
                                        <option value="day">Daily</option>
                                        <option value="week">Weekly</option>
                                        <option value="month">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label for="txt-chart-name" class="control-label col-md-4">
                                    Chart Name
                                </label>
                                <div class="col-md-8">
                                    <input type="text" id="txt-chart-name" class="form-control" v-model="name">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <a class="btn btn-primary btn-flat" href="#" @click.prevent="addNewWidget">CONFIRM</a>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
        <loading v-if="isLoadingCategories || isLoadingProducts || isLoadingSites || isAddingNewWidget"></loading>
    </div>
</template>

<script>

    import {
        LOAD_USER_DOMAINS
    } from '../../../../actions/action-types';

    import loading from '../../../fragments/loading/Loading.vue';
    import domain from '../../../../filters/domain';

    export default{
        components: {
            loading,
        },
        data(){
            return {
                categories: [],
                products: [],
                sites: [],
                selectedCategory: '',
                selectedProduct: '',
                selectedSite: '',
                timespan: 'this_week',
                resolution: 'day',
                type: 'category',
                name: '',
                isLoadingCategories: false,
                isLoadingProducts: false,
                isLoadingSites: false,
                isAddingNewWidget: false,
                errors: {},
            }
        },
        mounted(){
            console.info('AddWidget component mounted.');
            this.loadUserDomains();
            this.loadCategories();
        },
        methods: {
            onChangeType(){
                switch (this.type) {
                    case 'category':
                        this.products = [];
                        this.sites = [];
                        this.selectedProduct = '';
                        this.selectedSite = '';
                        break;
                    case 'product':
                        if (this.selectedCategory != '' && this.products.length == 0) {
                            this.loadProducts();
                            this.selectedProduct = '';
                        }
                        this.sites = [];
                        this.selectedSite = '';
                        break;
                    case 'site':
                        if (this.selectedCategory != '' && this.products.length == 0) {
                            this.loadProducts();
                            this.selectedProduct = '';
                        }
                        if (this.selectedCategory !== '' && this.selectedProduct !== '' && this.sites.length === 0) {
                            this.loadSites();
                            this.selectedSite = '';
                        }
                        break;
                }
            },
            onCategoryChange(){
                if (this.type == 'product' || this.type == 'site') {
                    this.loadProducts();
                }
                this.selectedProduct = '';
                this.selectedSite = '';
            },
            onProductChange(){
                if (this.type == 'site') {
                    this.loadSites();
                }
                this.selectedSite = '';
            },
            addNewWidget(){
                this.isAddingNewWidget = true;
                axios.post('dashboard/widget', this.addNewWidgetRequestData).then(response => {
                    this.isAddingNewWidget = false;
                    if (response.data.status === true) {
                        this.emitAddedNewWidget();
                    }
                }).catch(error => {
                    this.isAddingNewWidget = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            loadCategories(){
                this.isLoadingCategories = true;
                axios.get('/category', this.loadCategoriesRequestData).then(response => {
                    this.isLoadingCategories = false;
                    if (response.data.status === true) {
                        this.categories = response.data.categories;
                    }
                }).catch(error => {
                    this.isLoadingCategories = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            loadProducts(){
                this.isLoadingProducts = true;
                axios.get('/product', this.loadProductsRequestData).then(response => {
                    this.isLoadingProducts = false;
                    if (response.data.status === true) {
                        this.products = response.data.products;
                    }
                }).catch(error => {
                    this.isLoadingProducts = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            loadSites(){
                this.isLoadingSites = true;
                axios.get('/site', this.loadSitesRequestData).then(response => {
                    this.isLoadingSites = false;
                    if (response.data.status === true) {
                        this.sites = response.data.sites;
                    }
                }).catch(error => {
                    this.isLoadingSites = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            loadUserDomains(){
                this.$store.dispatch(LOAD_USER_DOMAINS);
            },
            emitAddedNewWidget(){
                this.$emit('added-widget');
            },
            hideModal(){
                this.$emit('hide-modal');
            },
            displayName(site){
                /*TODO need to add ebay site store name before the following validations*/
                if (site.item !== null && site.item.sellerUsername !== null) {
                    return "eBay: " + site.item.sellerUsername;
                }
                let siteDomain = this.$options.filters.domain(site.siteUrl);
                let userDomains = this.userDomains.filter(userDomain => {
                    let domain = this.$options.filters.domain(userDomain.domain);
                    return siteDomain.indexOf(domain) > -1;
                });
                if (userDomains.length > 0) {
                    let userDomain = userDomains[0];
                    if (userDomain.alias !== null && userDomain.alias.length > 0) {
                        return userDomain.alias;
                    }
                }
                return siteDomain;
            },
        },
        computed: {
            loadCategoriesRequestData(){
                return {
                    params: {}
                }
            },
            loadProductsRequestData(){
                return {
                    params: {
                        category_id: this.selectedCategory,
                    }
                }
            },
            loadSitesRequestData(){
                return {
                    params: {
                        product_id: this.selectedProduct,
                    }
                }
            },
            addNewWidgetRequestData(){
                switch (this.type) {
                    case 'category':
                        return {
                            type: this.type,
                            id: this.selectedCategory,
                            timespan: this.timespan,
                            resolution: this.resolution,
                            name: this.name,
                        };
                        break;
                    case 'product':
                        return {
                            type: this.type,
                            id: this.selectedProduct,
                            timespan: this.timespan,
                            resolution: this.resolution,
                            name: this.name,
                        };
                        break;
                    case 'site':
                        return {
                            type: this.type,
                            id: this.selectedSite,
                            timespan: this.timespan,
                            resolution: this.resolution,
                            name: this.name,
                        };
                }
                return {};
            },
//            products(){
//                if (this.selectedCategory !== null && this.selectedCategory !== '') {
//                    let selectedCategory = this.categories.filter(category => {
//                        return this.selectedCategory === category.id;
//                    });
//                    console.info('selectedCategory', selectedCategory);
//                    if (selectedCategory.length > 0) {
//                        selectedCategory = selectedCategory[0];
//                        return selectedCategory.products;
//                    }
//                }
//                return [];
//            },
//            sites(){
//                if (this.products.length > 0 && this.selectedProduct !== null && this.selectedProduct !== '') {
//                    let selectedProduct = this.products.filter(product => {
//                        return this.selectedProduct === product.id;
//                    });
//                    if (selectedProduct.length > 0) {
//                        selectedProduct = selectedProduct[0];
//                        return selectedProduct.sites;
//                    }
//                }
//                return [];
//            },
            showProductControl(){
                return this.products.length > 0 && this.selectedCategory !== '' && (this.type === 'product' || this.type === 'site');
            },
            showSiteControl(){
                return this.products.length > 0 && this.selectedCategory !== '' && this.selectedProduct !== '' && this.type === 'site';
            },
            userDomains(){
                return this.$store.getters.userDomains;
            },
        }
    }
</script>

<style>
    .required label::after {
        content: " *";
        color: #ff0000;
    }
</style>