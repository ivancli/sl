<template>
    <table class="table table-condensed product-wrapper">
        <thead>
        <tr>
            <th class="shrink product-th">
                <a class="btn-collapse btn-product-dragger" href="#">
                    <i class="fa fa-tag"></i>
                </a>
            </th>
            <th class="product-th">
                <a class="text-muted product-name-link" href="#" v-text="product.name" v-show="!editingProductName"></a>
                <edit-product :editing-product="product" @edited-product="editedProduct" @edit-product-name="goingToEditProductName"
                              @cancel-edit-product-name="cancelEditProductName"></edit-product>
            </th>
            <th class="text-right action-cell product-th">
                <a href="#" class="btn-action" title="chart">
                    <i class="fa fa-line-chart"></i>
                </a>
                <a href="#" class="btn-action" title="report">
                    <i class="fa fa-envelope-o"></i>
                </a>
                <a href="#" class="btn-action" title="delete">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </th>
            <th class="text-center vertical-align-middle product-collapse-cell" width="70">
                <a class="text-muted btn-collapse collapsed">
                    <i class="fa fa-angle-up"></i>
                </a>
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
                <div class="collapsible-product-div collapse in">
                    <table class="table table-striped table-condensed tbl-site">
                        <thead>
                        <tr>
                            <th width="15%">Site</th>
                            <th class="text-center hidden-xs" width="10%">My Site</th>
                            <th width="10%" class="text-right">Current Price</th>
                            <th width="10%" class="text-right">Previous Price</th>
                            <th width="10%" class="hidden-xs text-right">Change</th>
                            <th width="10%" class="hidden-xs" style="padding-left: 20px;">Last Changed</th>
                            <th>Updated</th>
                            <th>Tracked Since</th>
                            <th width="100px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <single-site v-for="site in sites" :current-site="site" @reload-sites="loadSites"></single-site>
                        <tr class="add-site-row">
                            <td colspan="9" class="add-item-cell">
                                <add-site :product="product" @addedSite="loadSites"></add-site>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import addSite from './AddSite.vue';
    import singleSite from './SingleSite.vue';
    import editProduct from './EditProduct.vue';
    import formatDateTime from '../../../filters/formatDateTime';

    export default {
        components: {
            addSite,
            singleSite,
            editProduct,
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
                editingProductName: false
            }
        },
        methods: {
            loadSites: function () {
                axios.get('/site', this.loadSitesRequestData).then(response=> {
                    if (response.data.status == true) {
                        this.sites = response.data.sites;
                    }
                }).catch(error=> {
                    console.info(error.response);
                })
            },
            goingToEditProductName: function () {
                this.editingProductName = true;
            },
            cancelEditProductName: function () {
                this.editingProductName = false;
            },
            reloadProducts: function () {
                this.$emit('reload-products');
            },
            editedProduct: function () {
                this.editingProductName = false;
                this.reloadProducts();
            }
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
        }
    }
</script>

<style>
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

    .product-collapse-cell {
        background-color: #e8e8e8;
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
        width: 100px;
    }

    .collapsible-category-div table.product-wrapper > thead > tr > th:first-child, .collapsible-category-div table.product-wrapper > tbody > tr > td:first-child {
        padding-left: 15px !important;
    }

    .collapsible-category-div table.product-wrapper thead tr:first-child th {
        padding-top: 10px;
    }

    tr.add-site-row {
        background-color: #fff !important;
    }
</style>