<template>
    <table class="table table-condensed tbl-category">
        <thead>
        <tr>
            <th class="shrink category-th">
                <a class="btn-collapse" aria-expanded="true"><i class="fa fa-tag "></i></a>
            </th>
            <th class="category-th">
                <a class="text-muted category-name-link" href="#" v-text="currentCategory.name"
                   v-show="!editingCategoryName"></a>
                <edit-category :editing-category="category" @edit-category-name="goingToEditCategoryName" @cancel-edit-category-name="cancelEditCategoryName"></edit-category>
            </th>

            <th class="text-right action-cell category-th">
                <a href="#" class="btn-action btn-chart" title="chart">
                    <i class="fa fa-line-chart"></i>
                </a>
                <a href="#" class="btn-action btn-report" title="report">
                    <i class="fa fa-envelope-o"></i>
                </a>
                <a href="#" class="btn-action btn-delete-category" title="delete">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </th>
            <th class="text-center vertical-align-middle" style="background-color: #d3d3d3;" width="70">
                <a class="text-muted btn-collapse" style="font-size: 35px;">
                    <i class="fa fa-angle-up"></i>
                </a>
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3" class="category-th">
                <div class="text-light">
                    Created
                    on {{currentCategory.created_at}}
                    <strong class="text-muted"><i>by {{currentCategory.owner.fullName}}</i></strong>
                </div>
                <div class="text-light">
                    Product URLs Tracked:
                    <strong><span class="lbl-site-usage text-muted"
                                  v-text="currentCategory.numberOfSites"></span></strong>
                </div>
            </th>
        </tr>

        <tr>
            <th></th>
            <th colspan="3" class="category-th action-cell add-item-cell">
                <add-product :category="currentCategory" @addedProduct="loadProducts"></add-product>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td colspan="3" class="table-container">
                <div class="collapsible-category-div collapse in">
                    <single-product v-for="product in products" :current-product="product"></single-product>
                </div>
            </td>
        </tr>
        <!--<tr>-->
        <!--<td></td>-->
        <!--<td colspan="3" class="table-container">-->
        <!--<div class="collapse collapsible-category-div in" data-products-url="http://login.spotlite.com.au/product/category/313" aria-expanded="true">-->
        <!--<div class="row">-->
        <!--<div class="col-sm-12 text-center">-->
        <!--<div class="dotdotdot loading-products" style="margin: 20px auto; display: none;">-->

        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</td>-->
        <!--</tr>-->
        </tbody>
    </table>
</template>

<script>
    import addProduct from './AddProduct.vue';
    import singleProduct from './SingleProduct.vue';
    import editCategory from './EditCategory.vue';

    export default {
        components: {
            addProduct,
            singleProduct,
            editCategory,
        },
        props: [
            'current-category'
        ],
        mounted() {
            console.info('SingleCategory component is mounted');
            this.loadProducts();
        },
        data() {
            return {
                products: [],
                editingCategoryName: false
            }
        },
        methods: {
            loadProducts: function () {
                axios.get('/product', this.loadProductsRequestData).then(response=> {
                    if (response.data.status == true) {
                        this.products = response.data.products;
                    }
                }).catch(error=> {
                    console.info(error.response);
                })
            },
            goingToEditCategoryName: function () {
                this.editingCategoryName = true;
            },
            cancelEditCategoryName: function () {
                this.editingCategoryName = false;
            }

        },
        computed: {
            category(){
                return this.currentCategory;
            },
            loadProductsRequestData(){
                return {
                    params: {
                        category_id: this.category.id
                    }
                }
            }
        }
    }
</script>

<style>
    .category-th, .product-th {
        vertical-align: middle !important;
        height: 40px !important;
    }

    .tbl-category {
        background-color: #f0f0f0;
    }

    .tbl-category th, .tbl-category td {
        border: none !important;
    }

    .tbl-category.table > thead > tr > th {
        border-bottom: none;
        border-top: none;
    }

    .btn-collapse .fa-tag {
        font-size: 30px;
        margin-left: 10px;
    }

    .btn-action, .btn-collapse {
        color: #8c8c8c;
    }

    .category-name-link {
        font-size: 18px;
        line-height: 46px;
    }

    .category-name-link:hover {
        color: #777;
        cursor: default;
    }

    .btn-action {
        padding-left: 3px;
        padding-right: 3px;
        display: inline-block;
        font-size: 16px;
        vertical-align: middle;
    }

    .category-th.action-cell {
        padding-right: 25px;
        width: 200px;
    }

    .category-th {
        vertical-align: middle !important;
        height: 40px !important;
    }

    th.category-th.action-cell.add-item-cell {
        padding-right: 20px !important;
    }

    .collapsible-category-div table.product-wrapper {
        background-color: #fff;
        margin-bottom: 10px;
        -webkit-box-shadow: -5px 4px 10px -5px rgba(219, 219, 219, 1);
        -moz-box-shadow: -5px 4px 10px -5px rgba(219, 219, 219, 1);
        box-shadow: -5px 4px 10px -5px rgba(219, 219, 219, 1);
    }

    table td.table-container {
        padding-right: 20px !important;
        padding-left: 0 !important;
    }
</style>