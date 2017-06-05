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
                        <select class="form-control sl-form-control input-sm" v-model="paginationData.per_page" @change="loadProducts(currentPageUrl)">
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
                                    <th :class="orderByClass('cateogry.category_name')" @click.prevent="setOrdering('category_name')">Category</th>
                                    <th :class="orderByClass('product_name')" @click.prevent="setOrdering('product_name')">Product</th>
                                    <th :class="orderByClass('ref_price')" @click.prevent="setOrdering('ref_price')">Reference site price</th>
                                    <th :class="orderByClass('cheapest')" @click.prevent="setOrdering('cheapest')">Cheapest</th>
                                    <th :class="orderByClass('cheapest_price')" @click.prevent="setOrdering('cheapest_price')">Cheapest $</th>
                                    <th :class="orderByClass('diff_price')" @click.prevent="setOrdering('diff_price')">Difference $</th>
                                    <th :class="orderByClass('diff_percent')" @click.prevent="setOrdering('diff_percent')">Difference %</th>
                                </tr>
                                </thead>
                                <tbody v-if="products.length > 0">
                                <single-product-row v-for="product in products" :current-product="product"></single-product-row>
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
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadProducts" :disabled="paginationData.current_page == 1">FIRST</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadProducts(prevPageUrl)" :disabled="prevPageUrl == null">PREV</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadProducts(nextPageUrl)" :disabled="nextPageUrl == null">NEXT</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadProducts(lastPageUrl)" :disabled="paginationData.current_page == paginationData.last_page || paginationData.last_page == null">LAST</button>
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
                    current_page: 1,
                    from: null,
                    last_page: null,
                    next_page: null,
                    per_page: 25,
                    prev_page: null,
                    to: null,
                    total: null,
                },
                orderByData: {
                    column: 'id',
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
            loadProducts(link){
                this.isLoadingProduct = true;
                if (typeof link !== 'string') {
                    link = this.firstPageUrl;
                }
                axios.get(link).then(response => {
                    this.isLoadingProduct = false;
                    if (response.data.status === true) {
                        this.products = response.data.products.data;
                        this.paginationData.current_page = response.data.urls.current_page;
                        this.paginationData.last_page = response.data.urls.last_page;
                        this.paginationData.next_page = response.data.urls.next_page;
                        this.paginationData.per_page = response.data.urls.per_page;
                        this.paginationData.prev_page = response.data.urls.prev_page;
                        this.paginationData.to = response.data.urls.to;
                        this.paginationData.total = response.data.urls.total;
                    }
                }).catch(error => {
                    this.isLoadingProduct = false;
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

                this.loadProducts(this.currentPageUrl);
            },
            onFilterChanged(){
                if (this.filterDelayData.promise !== null) {
                    clearTimeout(this.filterDelayData.promise);
                }
                this.filterDelayData.promise = setTimeout(() => {
                    this.paginationData.current_page = 1;
                    this.loadProducts(this.currentPageUrl);
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
        },
        computed: {
            user(){
                return this.$store.getters.user;
            },
            currentPageUrl: function () {
                return '/positioning?page=' + this.paginationData.current_page
                    + '&orderBy=' + this.orderByData.column
                    + '&direction=' + this.orderByData.direction
                    + '&per_page=' + this.paginationData.per_page
                    + '&key=' + this.filterText
                    + '&reference=' + this.reference
                    + '&position=' + this.position
                    + '&category=' + this.category
                    + '&exclude=' + this.exclude
                    + '&brand=' + this.brand
                    + '&supplier=' + this.supplier;
            },
            nextPageUrl: function () {
                if (this.paginationData.next_page === null) {
                    return null;
                } else {
                    return '/positioning?page=' + this.paginationData.next_page
                        + '&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText
                        + '&reference=' + this.reference
                        + '&position=' + this.position
                        + '&category=' + this.category
                        + '&exclude=' + this.exclude
                        + '&brand=' + this.brand
                        + '&supplier=' + this.supplier;
                }
            },
            prevPageUrl: function () {
                if (this.paginationData.prev_page === null) {
                    return null;
                } else {
                    return '/positioning?page=' + this.paginationData.prev_page
                        + '&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText
                        + '&reference=' + this.reference
                        + '&position=' + this.position
                        + '&category=' + this.category
                        + '&exclude=' + this.exclude
                        + '&brand=' + this.brand
                        + '&supplier=' + this.supplier;
                }
            },
            firstPageUrl: function () {
                return '/positioning?page=1&orderBy=' + this.orderByData.column
                    + '&direction=' + this.orderByData.direction
                    + '&per_page=' + this.paginationData.per_page
                    + '&key=' + this.filterText
                    + '&reference=' + this.reference
                    + '&position=' + this.position
                    + '&category=' + this.category
                    + '&exclude=' + this.exclude
                    + '&brand=' + this.brand
                    + '&supplier=' + this.supplier;
            },
            lastPageUrl: function () {
                if (this.this.paginationData.last_page === null) {
                    return null;
                } else {
                    return '/positioning?page=' + this.paginationData.last_page
                        + '&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText
                        + '&reference=' + this.reference
                        + '&position=' + this.position
                        + '&category=' + this.category
                        + '&exclude=' + this.exclude
                        + '&brand=' + this.brand
                        + '&supplier=' + this.supplier;
                }
            },
        }
    }
</script>

<style>

</style>