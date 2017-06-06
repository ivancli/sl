<template>
    <table class="table table-condensed product-wrapper">
        <thead>
        <tr>
            <th class="shrink product-th">
                <a class="btn-collapse btn-product-dragger" href="#">
                    <i class="fa fa-tag"></i>
                </a>
            </th>
            <th class="product-th product-name-container">
                <a class="text-muted product-name-link" href="#" v-text="product.product_name" v-show="!editingProduct"></a>
                <edit-product :editing-product="product" :going-to-edit-product="editingProduct" @edited-product="editedProduct" @cancel-edit-product-name="cancelEditProduct"></edit-product>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fa fa-info-circle text-muted" v-show="!editingProduct"></i>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span v-show="!editingProduct" v-if="cheapestSite != null" class="hidden-xs hidden-sm f-w-normal text-muted f-s-11">
                    <span>
                        Cheapest:
                        <strong>
                            <a class="text-muted" :href="cheapestSite.siteUrl" target="_blank">
                                {{ cheapestDisplayName }}
                            </a>
                        </strong>
                    </span>
                    &nbsp;&nbsp;
                    <span>
                        Current Price:
                        <strong>${{ cheapestSite.item.recentPrice | currency }}</strong>
                    </span>
                    &nbsp;&nbsp;
                    <span>
                        Price Change:
                        <strong v-if="cheapestSite.item.priceChange != null">{{ cheapestSite.item.priceChange < 0 ? '-' : '' }} ${{ cheapestSite.item.priceChange | currency }}</strong>
                        <strong v-else><i class="fa fa-minus"></i></strong>
                    </span>
                </span>
            </th>
            <th class="text-right action-cell product-th">
                <a href="#" class="btn-action" title="edit" @click.prevent="onClickEditProduct" v-if="!editingProduct && subscriptionIsValid">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="#" class="btn-action" title="chart" v-if="numberOfSites > 0" @click.prevent="onClickViewChart">
                    <i class="fa fa-line-chart"></i>
                </a>
                <a href="#" class="btn-action" title="report" @click.prevent="onClickEditProductReport" v-if="subscriptionIsValid">
                    <i class="fa" :class="reportIconClass"></i>
                </a>
                <a href="#" class="btn-action" title="delete" @click.prevent="onClickDeleteProduct">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </th>
            <th class="text-center vertical-align-middle product-collapse-cell" width="70">
                <div @click.prevent="toggleProductCollapse">
                    <a class="text-muted btn-collapse" :class="isProductCollapsed ? 'collapsed' : ''">
                        <i class="fa fa-angle-up"></i>
                    </a>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td colspan="3">
                <div class="text-light">
                    Created on {{product.created_at | formatDateTime(dateFormat)}} <strong class="text-muted"><i>by {{user.fullName}}</i></strong>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" class="table-container">
                <div class="collapsible-product-div collapse in" v-show="!isProductCollapsed">
                    <table class="table table-striped table-condensed tbl-site">
                        <thead>
                        <tr>
                            <th class="sortable" @click.prevent="sortSitesBy('site')" :class="sortSiteClass('site')">Site</th>
                            <th class="sortable text-right" @click.prevent="sortSitesBy('recent_price')" :class="sortSiteClass('recent_price')">Current Price</th>
                            <th class="sortable hidden-xs hidden-sm text-center" :class="sortSiteClass('availability')" @click.prevent="sortSitesBy('availability')">Available</th>
                            <th class="sortable hidden-xs hidden-sm text-right" :class="sortSiteClass('previous_price')" @click.prevent="sortSitesBy('previous_price')">Previous Price</th>
                            <th class="sortable text-right hidden-xs hidden-sm" :class="sortSiteClass('price_change')" @click.prevent="sortSitesBy('price_change')">Change</th>
                            <th class="sortable hidden-xs hidden-sm" @click.prevent="sortSitesBy('last_changed_at')" :class="sortSiteClass('last_changed_at')" style="padding-left: 20px;">Last Changed</th>
                            <th width="120"></th>
                        </tr>
                        </thead>
                        <single-site v-for="site in sites" :current-site="site" :is-newly-created="justAddedSiteId == site.id" @selected-item="selectedItem" @reload-site="updateSite" @reload-sites="reloadSites" @deleted-site="deletedSite"></single-site>
                        <tbody>
                        <tr class="empty-message-row" v-if="!hasSites && !isLoadingSites">
                            <td colspan="9" class="text-center">
                                To start tracking prices, simply copy and paste the URL of the product page of the website your want to track.
                            </td>
                        </tr>
                        <tr class="loading-row loading-row-text" v-if="isLoadingSites && !hasSites">
                            <td colspan="9" class="text-center">
                                Please wait while SpotLite is loading your URLs.
                            </td>
                        </tr>
                        </tbody>
                        <!--DOT DOT DOT-->
                        <tbody v-if="isLoadingSites">
                        <tr class="loading-row">
                            <td colspan="9" class="text-center p-t-20">
                                <dotdotdot></dotdotdot>
                            </td>
                        </tr>
                        </tbody>
                        <!--/DOT DOT DOT-->
                        <!--LOAD MORE-->
                        <!--IMPORTANT: disable load more function for the moment, progressive loading logic conflicts with sorting when added new site-->
                        <!--<tbody>-->
                        <!--<tr class="load-more-row" v-if="!isLoadingSites && moreSitesToLoad">-->
                        <!--<td colspan="9" class="p-t-20">-->
                        <!--<a href="#" class="lnk-load-more text-tiffany" @click.prevent="loadSites">LOAD MORE&hellip;</a>-->
                        <!--</td>-->
                        <!--</tr>-->
                        <!--</tbody>-->
                        <!--/LOAD MORE-->
                        <tbody v-if="subscriptionIsValid">
                        <tr class="add-site-row">
                            <td colspan="9" class="add-item-cell">
                                <add-site :product="product" :number-of-sites="numberOfSites" @added-site="addedSite"></add-site>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
        <product-report-popup v-if="editingProductReport" :current-product="product" @edited-report="editedProductReport" @deleted-report="deletedProductReport" @cancel-edit="cancelEditProductReport"></product-report-popup>
        <product-chart v-if="isViewingChart" :charting-product="product" @hide-modal="cancelViewingChart"></product-chart>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
    </table>
</template>

<script>
    import Vue from 'vue';

    import addSite from './AddSite.vue';
    import singleSite from './SingleSite.vue';
    import editProduct from './EditProduct.vue';
    import productChart from './chart/ProductChart.vue';
    import productReportPopup from '../report/popups/Product.vue';

    import domain from '../../../filters/domain';
    import currency from '../../../filters/currency';
    import formatDateTime from '../../../filters/formatDateTime';

    import dotdotdot from '../../fragments/loading/DotDotDot.vue';
    import deleteConfirmation from '../../fragments/modals/DeleteConfirmation.vue';

    import {
        LOAD_USER, CLEAR_PRODUCT_SEARCH_PROMISE
    } from '../../../actions/action-types';

    export default {
        components: {
            addSite,
            singleSite,
            editProduct,
            productReportPopup,
            productChart,
            dotdotdot,
            deleteConfirmation,
        },
        props: [
            'current-product'
        ],
        mounted() {
            this.loadSites();
        },
        data() {
            return {
                sites: [],
                editingProduct: false,
                deleteParams: {
                    title: 'product',
                    list: [
                        'All URLs you have added',
                        'All Product charts generated, including any Charts displayed on your Dashboards',
                        'All Product Reports generated',
                        'All Alerts set up for this Product',
                        'This Product\'s pricing information tracked to date'
                    ],
                    active: false
                },
                isLoadingSites: false,
                isDeletingProduct: false,
                isProductCollapsed: false,
                justAddedSite: null,
                siteLength: null,
                sorting: {
                    column: 'recent_price',
                    sequence: 'asc',
                },
                editingProductReport: false,
                isViewingChart: false,
            }
        },
        watch: {
            productSearchPromise(val){
                if (val === true) {
                    this.clearProductSearchPromise();
                    this.reloadSites();
                }
            }
        },
        methods: {
            /*region sites related methods*/
            addedSite(site) {
                if (site.url.itemsCount > 1) {
                    this.justAddedSite = site;
                }
                this.reloadSites();
            },
            appendSite(site) {
                this.sites.push(site);
            },
            updateSite(site){
                this.reloadSite(site, newSite => {
                    this.setSite(newSite);
                    this.emitReloadProduct();
                });
            },
            reloadSite(site, callback){
                axios.get(site.urls.show).then(response => {
                    if (response.data.status === true) {
                        if (typeof callback === 'function') {
                            callback(response.data.site);
                        }
                    }
                }).catch(error => {
                    console.info(error)
                })
            },
            setSite(newSite){
                let index = this.sites.findIndex(site => newSite.id === site.id);
                Vue.set(this.sites, index, newSite);
            },
            reloadSites(){
                this.clearSitesList();
                this.loadSites();
            },
            loadSites(){
                this.isLoadingSites = true;
                axios.get('/site', this.loadSitesRequestData).then(response => {
                    this.isLoadingSites = false;
                    if (response.data.status === true) {
                        this.sites = this.sites.concat(response.data.sites);
                    }
                }).catch(error => {
                    this.isLoadingSites = false;
                    console.info(error.response);
                })
            },
            deletedSite(site){
                this.spliceSite(site);
                this.emitReloadProduct();
            },
            spliceSite(deletedSite){
                let index = this.sites.findIndex(site => deletedSite.id === site.id);
                this.sites.splice(index, 1);
            },
            selectedItem(item){
                this.justAddedSite = null;
            },
            clearSitesList(){
                this.sites = [];
            },
            sortSitesBy(column){
                if (this.sorting.column === column) {
                    if (this.sorting.sequence === 'asc') {
                        this.sorting.sequence = 'desc';
                    } else {
                        this.sorting.sequence = 'asc';
                    }
                } else {
                    this.sorting.column = column;
                    this.sorting.sequence = 'asc';
                }
                this.reloadSites();
            },
            /*endregion*/
            /*region product related methods*/
            onClickEditProduct(){
                this.editingProduct = true;
            },
            cancelEditProduct(){
                this.editingProduct = false;
            },
            editedProduct(){
                this.editingProduct = false;
                this.emitReloadProduct();
            },
            onClickDeleteProduct(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteProduct()
            },
            deleteProduct(){
                this.isDeletingProduct = true;
                axios.delete(this.product.urls.delete).then(response => {
                    this.isDeletingProduct = false;
                    if (response.data.status === true) {
                        this.loadUser();
                        this.emitDeleteProduct();
                    }
                }).catch(error => {
                    this.isDeletingProduct = false;
                })
            },
            toggleProductCollapse(){
                this.isProductCollapsed = !this.isProductCollapsed;
            },
            clearProductSearchPromise(){
                this.$store.dispatch(CLEAR_PRODUCT_SEARCH_PROMISE, {
                    product_id: this.product.id
                });
            },
            onClickEditProductReport(){
                this.editingProductReport = true;
            },
            editedProductReport(){
                this.editingProductReport = false;
                this.emitReloadProduct();
            },
            deletedProductReport(){
                this.editingProductReport = false;
                this.emitReloadProduct();
            },
            cancelEditProductReport(){
                this.editingProductReport = false;
            },
            onClickViewChart(){
                this.isViewingChart = true;
            },
            cancelViewingChart(){
                this.isViewingChart = false;
            },
            /*endregion*/
            loadUser(){
                this.$store.dispatch(LOAD_USER);
            },
            /*region emit events*/
            emitReloadProduct(){
                this.$emit('reload-product', this.product);
            },
            emitReloadProducts(){
                this.$emit('reload-products');
            },
            emitDeleteProduct(){
                this.$emit('deleted-product', this.product);
            },
            /*endregion*/
            /*region helpers*/
            sortSiteClass(column){
                if (this.sorting.column === column) {
                    return 'sort-' + this.sorting.sequence;
                } else {
                    return ''
                }
            }
            /*endregion*/
        },
        computed: {
            user(){
                return this.$store.getters.user;
            },
            subscription(){
                if (typeof this.user.subscription !== 'undefined' && this.user.subscription !== null) {
                    return this.user.subscription;
                }
                return null;
            },
            subscriptionIsValid(){
                if (this.subscription !== null) {
                    return this.subscription.isValid;
                }
                return true;
            },
            product(){
                return this.currentProduct;
            },
            loadSitesRequestData(){
                return {
                    params: {
                        offset: this.sites.length,
                        length: this.siteLength,
                        product_id: this.product.id,
                        sorting_column: this.sorting.column,
                        sorting_sequence: this.sorting.sequence,
                    }
                }
            },
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
            },
            hasSites(){
                return this.sites.length > 0;
            },
            justAddedSiteId(){
                if (this.justAddedSite !== null) {
                    return this.justAddedSite.id
                } else {
                    return null;
                }
            },
            productSearchTerm(){
                return this.$store.getters.productSearchTerm;
            },
            productSearchPromise(){
                return this.$store.getters.productSearchPromise[this.product.id] === true;
            },
            numberOfSites(){
                return this.product.numberOfSites;
            },
            moreSitesToLoad(){
                return this.sites.length < this.product.numberOfSites;
            },
            cheapestSite(){
                if (this.sites.length > 0) {
                    let cheapestPrice = null;
                    let cheapestSite = null;
                    this.sites.forEach(function (site) {
                        if (site.item !== null && site.item.recentPrice) {
                            let recentPrice = parseInt(site.item.recentPrice);
                            if (cheapestPrice === null || recentPrice < cheapestPrice) {
                                cheapestPrice = recentPrice;
                                cheapestSite = site;
                            }
                        }
                    });
                    return cheapestSite;
                }
                return null;
            },
            reportIconClass(){
                if (this.product.hasReport === true) {
                    return 'text-tiffany fa-envelope';
                } else {
                    return 'fa-envelope-o';
                }
            },
            userDomains(){
                return this.$store.getters.userDomains;
            },
            cheapestSellerUsername(){
                if (this.cheapestSite !== null && this.cheapestSite.item !== null) {
                    return this.cheapestSite.item.sellerUsername;
                }
                return null;
            },
            cheapestDisplayName(){
                /*TODO need to add ebay site store name before the following validations*/
                if (this.cheapestSellerUsername !== null) {
                    return "eBay: " + this.cheapestSellerUsername;
                }

                let siteDomain = this.$options.filters.domain(this.cheapestSite.siteUrl);

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
        }
    }
</script>

<style>
    .product-wrapper th.product-th {
        vertical-align: top;
        padding-top: 20px;
    }

    .product-wrapper th.product-th.product-name-container {
        padding-top: 10px;
    }

    .btn-collapse i.fa-angle-up {
        transform: rotate(-360deg);
        -moz-transform: rotate(-360deg);
        -ms-transform: rotate(-360deg);
        -o-transform: rotate(-360deg);
        -webkit-transform: rotate(-360deg);
        transition: transform 550ms ease;
        -moz-transition: -moz-transform 550ms ease;
        -ms-transition: -ms-transform 550ms ease;
        -o-transition: -o-transform 550ms ease;
        -webkit-transition: -webkit-transform 550ms ease;
    }

    .product-wrapper th.product-collapse-cell {
        padding: 0;
    }

    .product-collapse-cell div {
        cursor: pointer;
        background-color: #e8e8e8;
        height: 65px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .product-collapse-cell .btn-collapse {
        font-size: 30px;
    }

    .btn-edit.btn-edit-product {
        margin-left: 30px;
        color: #aaa;
        font-size: 12px;
    }

    .product-name-link:hover {
        color: #777;
        cursor: default;
    }

    .product-th:first-child {
        font-size: 19px;
    }

    .btn-product-dragger i.fa-tag {
        font-size: 18px;
    }

    .product-wrapper .btn-action {
        font-size: 14px;
    }

    .product-name-link {
        font-size: 15px;
        line-height: 46px;
    }

    .product-th.action-cell {
        padding-right: 25px;
        width: 200px;
    }

    .collapsible-category-div table.product-wrapper > thead > tr > th:first-child, .collapsible-category-div table.product-wrapper > tbody > tr > td:first-child {
        padding-left: 15px !important;
    }

    tr.empty-message-row td {
        font-weight: bold;
        padding: 20px !important;
        font-size: 16px;
        color: #777;
    }

    tr.loading-row-text {
        font-weight: bold;
        padding: 20px !important;
        font-size: 16px;
        color: #777;
    }

    tr.add-site-row, tr.loading-row, tr.load-more-row {
        background-color: #fff !important;
    }

    .table > tbody + tbody {
        border-top: none;
    }

    th.sortable {
        cursor: pointer;
    }

    th.sortable::after {
        font: normal normal normal 14px/1 FontAwesome;
        content: "\F0DC";
        padding-left: 10px;
        color: #d3d3d3;
    }

    th.sortable.sort-asc::after {
        font: normal normal normal 14px/1 FontAwesome;
        content: "\f0de";
        padding-left: 10px;
        color: #868686;
    }

    th.sortable.sort-desc::after {
        font: normal normal normal 14px/1 FontAwesome;
        content: "\f0dd";
        padding-left: 10px;
        color: #868686;
    }
</style>