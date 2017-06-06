<template>
    <!-- Main content -->
    <section class="content">
        <p class="text-muted f-s-17 m-b-20">The Positioning screen provides a powerful yet easy to use way of quickly seeing how your prices compare to the competitors across all categories and products.</p>

        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row m-b-20">
                    <div class="col-md-4">
                        <label for="sel-ref" class="control-label">Reference Site</label>
                        <select id="sel-ref" class="form-control" v-model="reference">
                            <option value=""></option>
                            <option :value="domainFilter" v-for="domainFilter in domainsFilter">
                                {{ domainFilter }}
                            </option>
                            <option :value="username" v-for="username in ebaySellerUsernamesFilter">
                                eBey: {{ username }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="sel-comp-position" class="control-label">Competitive Position</label>
                        <select id="sel-comp-position" class="form-control" v-model="position">
                            <option value="">All products</option>
                            <option value="not_cheapest">Reference site not cheapest</option>
                            <option value="most_expensive">Reference site most expensive</option>
                            <option value="cheapest">Reference site is cheapest</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="sel-category" class="control-label">Categories</label>
                        <select id="sel-category" class="form-control" v-model="category">
                            <option value="">All categories</option>
                            <option :value="categoryFilter.id" v-for="categoryFilter in categoriesFilter">
                                {{ categoryFilter.category_name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row m-b-20">
                    <div class="col-md-4">
                        <label for="sel-exclude" class="control-label">Exclude Competitors</label>
                        <select id="sel-exclude" class="form-control" v-model="exclude">
                            <option value=""></option>
                            <option :value="domainFilter" v-for="domainFilter in domainsFilter" v-if="reference != domainFilter">
                                {{ domainFilter }}
                            </option>
                            <option :value="username" v-for="username in ebaySellerUsernamesFilter" v-if="reference != username && reference != 'ebay.com'">
                                eBey: {{ username }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="sel-brand" class="control-label">Brand</label>
                        <select id="sel-brand" class="form-control" v-model="brand">
                            <option value="">All brands</option>
                            <option :value="brandFilter" v-for="brandFilter in brandsFilter">
                                {{ brandFilter }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="sel-supplier" class="control-label">Supplier</label>
                        <select id="sel-supplier" class="form-control" v-model="supplier">
                            <option value="">All suppliers</option>
                            <option :value="supplierFilter" v-for="supplierFilter in suppliersFilter">
                                {{ supplierFilter }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row m-b-20">
                    <div class="col-xs-6">
                        <button class="btn btn-primary btn-flat" @click.prevent="loadProducts">SHOW PRODUCTS</button>
                    </div>
                    <div class="col-xs-6 text-right">
                        <button class="btn btn-primary btn-flat" @click.prevent="exportPositioningView">EXPORT</button>
                    </div>
                </div>

                <div class="row m-b-15">
                    <div class="col-sm-6">
                        Show
                        &nbsp;&nbsp;
                        <select class="form-control sl-form-control input-sm" v-model="paginationData.length" @change="loadProducts()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        &nbsp;&nbsp;
                        URLs
                    </div>
                    <div class="col-sm-6 text-right">
                        Search
                        &nbsp;&nbsp;
                        <input type="text" class="form-control sl-form-control input-sm" v-model="filterText" @input="onFilterChanged">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-condensed table-paginated">
                                <thead>
                                <tr>
                                    <th :class="orderByClass('category_name')" @click.prevent="setOrdering('category_name')">Category</th>
                                    <th :class="orderByClass('product_name')" @click.prevent="setOrdering('product_name')">Product</th>
                                    <th :class="orderByClass('ref_price')" @click.prevent="setOrdering('ref_price')">Reference site price</th>
                                    <th :class="orderByClass('cheapest_site_url')" @click.prevent="setOrdering('cheapest_site_url')">Cheapest</th>
                                    <th :class="orderByClass('cheapest_recent_price')" @click.prevent="setOrdering('cheapest_recent_price')">Cheapest $</th>
                                    <th :class="orderByClass('diff_price')" @click.prevent="setOrdering('diff_price')">Difference $</th>
                                    <th :class="orderByClass('diff_percent')" @click.prevent="setOrdering('diff_percent')">Difference %</th>
                                </tr>
                                </thead>
                                <tbody v-if="products.length > 0">
                                <single-product-row v-if="product.cheapest_site_url !== null" v-for="product in products" :current-product="product"></single-product-row>
                                </tbody>
                                <tbody v-else>
                                <tr>
                                    <td colspan="7" class="text-center" v-text="emptyTableMsg"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6 text-right">
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="onClickFirstPage" :disabled="firstPageDisabled">FIRST</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="onClickPreviousPage" :disabled="prevPageDisabled">PREV</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="onClickNextPage" :disabled="nextPageDisabled">NEXT</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="onClickLastPage" :disabled="lastPageDisabled">LAST</button>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="isLoadingProducts || isLoadingFilters"></loading>
    </section>
</template>

<script>
    import singleProductRow from './SingleProductRow.vue';
    import loading from '../../fragments/loading/Loading.vue';

    export default{
        components: {
            singleProductRow,
            loading,
        },
        data(){
            return {
                isLoadingProducts: false,
                isLoadingFilters: false,
                products: [],
                emptyTableMsg: 'Click "SHOW PRODUCTS" button to load products.',
                paginationData: {
                    start: 0,
                    length: 25,
                    total: null,
                },
                orderByData: {
                    column: 'products.id',
                    direction: 'asc'
                },
                filterText: '',
                filterDelayData: {
                    promise: null,
                    delay: 500
                },
                domainsFilter: [],
                ebaySellerUsernamesFilter: [],
                categoriesFilter: [],
                brandsFilter: [],
                suppliersFilter: [],
                reference: '',
                position: '',
                category: '',
                exclude: '',
                brand: '',
                supplier: '',
            }
        },
        mounted(){
            console.info('Index component mounted.');
            this.loadFilterOptions();
        },
        methods: {
            loadFilterOptions(){
                this.isLoadingFilters = true;
                axios.get('/positioning/filter').then(response => {
                    this.isLoadingFilters = false;
                    if (response.data.status === true) {
                        let options = response.data.options;
                        this.domainsFilter = response.data.options.domains;
                        this.ebaySellerUsernamesFilter = response.data.options.ebaySellerUsernames;
                        this.categoriesFilter = response.data.options.categories;
                        this.brandsFilter = response.data.options.brands;
                        this.suppliersFilter = response.data.options.suppliers;
                    }
                }).catch(error => {
                    this.isLoadingFilters = false;
                    console.info(error.response);
                })
            },
            loadProducts(){
                this.isLoadingProducts = true;
                axios.get('/positioning', this.positioningRequestData).then(response => {
                    this.isLoadingProducts = false;
                    if (response.data.status === true) {
                        this.products = response.data.products.data;
                        this.paginationData.total = response.data.products.recordTotal;
                    }
                }).catch(error => {
                    this.isLoadingProducts = false;
                    console.info(error.response);
                })
            },
            orderByClass(column){
                return this.orderByData.column === column ? 'order-' + this.orderByData.direction : '';
            },
            setOrdering(column){
                if (this.orderByData.column === column) {
                    if (this.orderByData.direction === 'asc') {
                        this.orderByData.direction = 'desc';
                    } else {
                        this.orderByData.direction = 'asc';
                    }
                } else {
                    this.orderByData.column = column;
                    this.orderByData.direction = 'asc'
                }

                this.loadProducts();
            },
            onFilterChanged(){
                if (this.filterDelayData.promise !== null) {
                    clearTimeout(this.filterDelayData.promise);
                }
                this.filterDelayData.promise = setTimeout(() => {
                    this.loadProducts();
                }, this.filterDelayData.delay);
            },
            exportPositioningView(){
                window.location.href = '/positioning/export?'
                    + '&orderBy=' + this.orderByData.column
                    + '&direction=' + this.orderByData.direction
                    + '&key=' + this.filterText
                    + '&reference=' + this.reference
                    + '&position=' + this.position
                    + '&category=' + this.category
                    + '&exclude=' + this.exclude
                    + '&brand=' + this.brand
                    + '&supplier=' + this.supplier;
            },
            onClickFirstPage(){
                this.paginationData.start = 0;
                this.loadProducts();
            },
            onClickPreviousPage(){
                if (this.paginationData.start - this.paginationData.length <= 0) {
                    this.paginationData.start = 0;
                } else {
                    this.paginationData.start = this.paginationData.start - this.paginationData.length;
                }
                this.loadProducts();
            },
            onClickNextPage(){
                if (this.paginationData.total < this.paginationData.start + this.paginationData.length) {
                    this.paginationData.start = 0;
                } else {
                    this.paginationData.start = this.paginationData.start + this.paginationData.length;
                }
                this.loadProducts();
            },
            onClickLastPage(){
                if (this.paginationData.total > this.paginationData.length) {
                    let remainer = this.paginationData.total % this.paginationData.length;
                    this.paginationData.start = this.paginationData.total - remainer;
                }
                this.loadProducts();
            }
        },
        computed: {
            user(){
                return this.$store.getters.user;
            },
            positioningRequestData(){
                return {
                    params: {
                        start: this.paginationData.start,
                        length: this.paginationData.length,
                        orderBy: this.orderByData.column,
                        direction: this.orderByData.direction,
                        key: this.filterText,
                        reference: this.reference,
                        position: this.position,
                        category: this.category,
                        exclude: this.exclude,
                        brand: this.brand,
                        supplier: this.supplier,
                    }
                }
            },
            firstPageDisabled(){
                return this.paginationData.total === null || this.paginationData.start === 0;
            },
            prevPageDisabled(){
                return this.paginationData.total === null || this.paginationData.start === 0;
            },
            nextPageDisabled(){
                return this.paginationData.total === null || this.paginationData.total < this.paginationData.start + this.paginationData.length;
            },
            lastPageDisabled(){
                return this.paginationData.total === null || this.paginationData.total < this.paginationData.start + this.paginationData.length;
            },
        }
    }
</script>

<style>

    table.table-paginated th {
        padding-right: 20px !important;
        cursor: pointer;
        position: relative;
    }

    .table-paginated th.order-asc:after {
        content: "\F160";
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        right: 5px;
        position: absolute;
        color: #aaa;
        top: 50%;
        margin-top: -7px;
    }

    .table-paginated th.order-desc:after {
        content: "\F161";
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        right: 5px;
        position: absolute;
        color: #aaa;
        top: 50%;
        margin-top: -7px;
    }
</style>