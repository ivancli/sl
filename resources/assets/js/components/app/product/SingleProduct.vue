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
                <edit-product :editing-product="product" :going-to-edit-product="editingProduct" @edited-product="editedProduct" @cancel-edit-product-name="cancelEditProductName"></edit-product>
            </th>
            <th class="text-right action-cell product-th">
                <a href="#" class="btn-action" title="edit" @click.prevent="onClickEditProduct" v-if="!editingProduct">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="#" class="btn-action" title="chart">
                    <i class="fa fa-line-chart"></i>
                </a>
                <a href="#" class="btn-action" title="report">
                    <i class="fa fa-envelope-o"></i>
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
                    Created
                    on {{product.created_at | formatDateTime(dateFormat)}}
                    <strong class="text-muted"><i>by {{product.owner.fullName}}</i></strong>
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
                            <th>Site</th>
                            <th class="text-right">Current Price</th>
                            <th class="hidden-xs hidden-sm text-center">Available</th>
                            <th class="hidden-xs hidden-sm text-right">Previous Price</th>
                            <th class="text-right hidden-xs hidden-sm">Change</th>
                            <th class="hidden-xs hidden-sm" style="padding-left: 20px;">Last Changed</th>
                            <th width="100"></th>
                        </tr>
                        </thead>
                        <single-site v-for="site in sites" :current-site="site" :is-newly-created="justAddedSiteId == site.id" @selected-item="selectedItem" @reload-sites="reloadSites"
                                     @deleted-site="deletedSite"></single-site>
                        <tbody>
                        <tr class="empty-message-row" v-if="!hasSites">
                            <td colspan="9" class="text-center">
                                To start tracking prices, simply copy and paste the URL of the product page of the website your want to track.
                            </td>
                        </tr>
                        </tbody>
                        <tbody>
                        <tr class="add-site-row">
                            <td colspan="9" class="add-item-cell">
                                <add-site :product="product" @added-site="addedSite"></add-site>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete"
                             @confirmDelete="confirmDelete"></delete-confirmation>
    </table>
</template>

<script>
    import addSite from './AddSite.vue';
    import singleSite from './SingleSite.vue';
    import editProduct from './EditProduct.vue';

    import formatDateTime from '../../../filters/formatDateTime';

    import deleteConfirmation from '../../fragments/modals/DeleteConfirmation.vue';

    import {
        LOAD_USER, CLEAR_PRODUCT_SEARCH_PROMISE
    } from '../../../actions/action-types';

    export default {
        components: {
            addSite,
            singleSite,
            editProduct,
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
                isDeletingProduct: false,
                isProductCollapsed: false,
                justAddedSite: null,
            }
        },
        watch: {
            productSearchPromise(val){
                if (val == true) {
                    this.clearProductSearchPromise();
                    this.reloadSites();
                }
            }
        },
        methods: {
            loadSites(){
                axios.get('/site', this.loadSitesRequestData).then(response => {
                    if (response.data.status == true) {
                        this.sites = response.data.sites;
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            reloadSites(){
                this.loadSites();
                this.loadUser();
            },
            deletedSite(){
                this.reloadSites();
                this.$emit('deleted-site');
            },
            goingToEditProductName(){
                this.editingProduct = true;
            },
            cancelEditProductName(){
                this.editingProduct = false;
            },
            addedSite: function (site) {
                this.$emit('added-site');
                this.reloadSites();
                if (site.url.itemsCount > 1) {
                    this.justAddedSite = site;
                }
            },
            reloadProducts(){
                this.$emit('reload-products');
            },
            editedProduct(){
                this.editingProduct = false;
                this.reloadProducts();
            },
            /*delete product*/
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
                    if (response.data.status == true) {
                        this.$emit('reload-products');
                        /*TODO deteld-product*/
                    }
                }).catch(error => {
                    this.isDeletingProduct = false;
                })
            },
            toggleProductCollapse(){
                this.isProductCollapsed = !this.isProductCollapsed;
            },
            loadUser(){
                this.$store.dispatch(LOAD_USER);
            },
            selectedItem(item){
                this.justAddedSite = null;
            },
            onClickEditProduct(){
                this.editingProduct = true;
            },
            clearProductSearchPromise(){
                this.$store.dispatch(CLEAR_PRODUCT_SEARCH_PROMISE, {
                    product_id: this.product.id
                });
            },
        },
        computed: {
            product(){
                return this.currentProduct;
            },
            loadSitesRequestData(){
                return {
                    params: {
                        product_id: this.product.id
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
                if (this.justAddedSite != null) {
                    return this.justAddedSite.id
                } else {
                    return null;
                }
            },
            productSearchTerm(){
                return this.$store.getters.productSearchTerm;
            },
            productSearchPromise(){
                return this.$store.getters.productSearchPromise[this.product.id] == true;
            }
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

    tr.add-site-row {
        background-color: #fff !important;
    }

    .table > tbody + tbody {
        border-top: none;
    }
</style>