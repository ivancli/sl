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
                <a class="text-muted product-name-link" href="#" v-text="product.name"></a>
                <!--<div class="input-group sl-input-group">-->
                <!--<input type="text" name="product_name" placeholder="Product Name" autocomplete="off" class="form-control sl-form-control input-lg product-name"-->
                <!--value="iPad Mini 2 32GB Wi-Fi (Silver)">-->
                <!--<span class="input-group-btn">-->
                <!--<button type="submit" class="btn btn-default btn-flat btn-lg">-->
                <!--<i class="fa fa-check"></i>-->
                <!--</button>-->
                <!--</span>-->
                <!--</div>-->

                <span class="btn-edit btn-edit-product">Edit &nbsp; <i class="fa fa-pencil-square-o"></i></span>
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
                    on {{product.created_at}}
                    <strong class="text-muted"><i>by {{product.owner.fullName}}</i></strong>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" class="table-container">
                <div id="product-559" class="collapsible-product-div collapse in" aria-expanded="true" data-sites-url="http://login.spotlite.com.au/site/product/559" data-start="2" data-length="10"
                     data-end="true">
                    <table class="table table-striped table-condensed tbl-site">
                        <thead>
                        <tr>
                            <th width="15%">Site</th>
                            <th class="text-center" width="10%">My Site</th>
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
                        <single-site v-for="site in sites" :current-site="site"></single-site>
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

    export default {
        components: {
            addSite,
            singleSite
        },
        props: [
            'current-product'
        ],
        mounted() {
            this.loadSites();
        },
        data: ()=> {
            return {
                sites: []
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
            }
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