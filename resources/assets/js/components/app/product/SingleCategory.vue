<template>
    <table class="table table-condensed tbl-category">
        <thead>
        <tr>
            <th class="shrink category-th">
                <a class="btn-collapse" aria-expanded="true"><i class="fa fa-tag "></i></a>
            </th>
            <th class="category-th">
                <a class="text-muted category-name-link" href="#" v-text="category.category_name"
                   v-show="!editingCategoryName"></a>
                <edit-category :editing-category="category" :going-to-edit-category-name="editingCategoryName"
                               @edited-category="editedCategory"
                               @cancel-edit-category-name="cancelEditCategoryName"></edit-category>
            </th>

            <th class="text-right action-cell category-th">
                <a href="#" class="btn-action btn-edit" title="edit" @click.prevent="onClickEditCategory"
                   v-if="!editingCategoryName">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="#" class="btn-action btn-chart" title="chart">
                    <i class="fa fa-line-chart"></i>
                </a>
                <a href="#" class="btn-action btn-report" title="report">
                    <i class="fa fa-envelope-o"></i>
                </a>
                <a href="#" class="btn-action btn-delete-category" title="delete"
                   @click.prevent="onClickDeleteCategory">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </th>
            <th class="text-center vertical-align-middle cell-category-collapse" width="70"
                @click.prevent="toggleCategoryCollapse">
                <a class="text-muted btn-collapse btn-category-collapse" :class="isCollapsed ? 'collapsed' : ''">
                    <i class="fa fa-angle-up"></i>
                </a>
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3" class="category-th">
                <div class="text-light">
                    Created
                    on {{category.created_at | formatDateTime(dateFormat)}}
                    <strong class="text-muted"><i>by {{category.owner.fullName}}</i></strong>
                </div>
                <div class="text-light">
                    Product URLs Tracked:
                    <strong><span class="lbl-site-usage text-muted" v-text="category.numberOfSites"></span></strong>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td colspan="3" class="table-container">
                <div class="collapsible-category-div collapse" :class="isCollapsed ? '' : 'in'">
                    <single-product v-for="product in products" :current-product="product"
                                    @reload-product="updateProduct"
                                    @reload-products="reloadProducts" @deleted-site="reloadCategories"></single-product>
                    <div class="text-center m-t-20" v-if="isLoadingProducts">
                        <dotdotdot></dotdotdot>
                    </div>
                    <div class="m-t-20" v-if="!isLoadingProducts && moreProductsToLoad">
                        <a href="#" class="lnk-load-more text-tiffany" @click.prevent="loadProducts">LOAD
                            MORE&hellip;</a>
                    </div>
                    <div class="m-t-20">
                        <add-product :category="category" @added-product="addedProduct"></add-product>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete"
                             @confirmDelete="confirmDelete"></delete-confirmation>
    </table>
</template>

<script>
    import Vue from 'vue';

    import addProduct from './AddProduct.vue';
    import singleProduct from './SingleProduct.vue';
    import editCategory from './EditCategory.vue';
    import formatDateTime from '../../../filters/formatDateTime';

    import dotdotdot from '../../fragments/loading/DotDotDot.vue';
    import deleteConfirmation from '../../fragments/modals/DeleteConfirmation.vue';

    import {
        SET_CATEGORY_COLLAPSE_STATUS, TOGGLE_COLLAPSE_CATEGORY, LOAD_USER, SET_PRODUCT_SEARCH_PROMISE
    } from '../../../actions/action-types';

    export default {
        components: {
            addProduct,
            singleProduct,
            editCategory,
            dotdotdot,
            deleteConfirmation
        },
        props: [
            'current-category'
        ],
        mounted() {
            console.info('SingleCategory component is mounted');
            this.setCategoryCollapseStatus(true);
            this.loadProducts();
        },
        data() {
            return {
                products: [],
                editingCategoryName: false,
                deleteParams: {
                    title: 'category',
                    list: [
                        'All Products you have added',
                        'All URLs you have added',
                        'All Category and Product Charts generated, including any Charts displayed on your Dashboards',
                        'All Category Reports generated',
                        'This Category\'s pricing information tracked to date'
                    ],
                    active: false
                },
                isLoadingProducts: false,
                isDeletingCategory: false,
                productLength: 5,
            }
        },
        watch: {
            categorySearchPromise(){
                if (this.categorySearchPromise == null) {
                    this.loadProducts(() => {
                        this.products.forEach((product) => {
                            this.$store.dispatch(SET_PRODUCT_SEARCH_PROMISE, {
                                product_id: product.id,
                                product_search_promise: true
                            });
                        })
                    });
                }
            }
        },
        methods: {
            loadProducts(callback) {
                this.isLoadingProducts = true;
                axios.get('/product', this.loadProductsRequestData).then(response => {
                    this.isLoadingProducts = false;
                    if (response.data.status == true) {
                        this.products = this.products.concat(response.data.products);
                        if (typeof callback == 'function') {
                            callback();
                        }
                    }
                }).catch(error => {
                    this.isLoadingProducts = false;
                    console.info(error.response);
                })
            },
            clearProductsList(){
                this.products = [];
            },
            updateProduct(product){
                this.reloadProduct(product, product => {
                    this.setProduct(product);
                    this.emitReloadCategory();
                });
            },
            reloadProduct(product, callback){
                axios.get(product.urls.show).then(response => {
                    if (response.data.status == true) {
                        if (typeof callback == 'function') {
                            callback(response.data.product);
                        }
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            setProduct(product){
                let index = this.products.findIndex(product => product.id === product.id);
                Vue.set(this.products, index, product);
            },
            reloadProducts() {
                this.clearProductsList();
                this.loadProducts();
                this.loadUser();
            },
            addedProduct(product) {
                this.loadUser();
                this.setCategoryCollapseStatus(false);
                this.reloadProducts();

                if (!this.moreProductsToLoad) {
                    this.appendProduct(product);
                }
                this.emitReloadCategory();
            },
            appendProduct(product){
                this.products.push(product);
            },
            goingToEditCategoryName() {
                this.editingCategoryName = true;
            },
            cancelEditCategoryName() {
                this.editingCategoryName = false;
            },
            reloadCategories() {
                this.$emit('reload-categories');
            },
            editedCategory() {
                this.editingCategoryName = false;
                this.reloadCategories();
            },
            toggleCategoryCollapse() {
                this.$store.dispatch(TOGGLE_COLLAPSE_CATEGORY, {
                    category_id: this.category.id
                });
            },
            setCategoryCollapseStatus(status) {
                this.$store.dispatch(SET_CATEGORY_COLLAPSE_STATUS, {
                    category_id: this.category.id,
                    status: status
                });
            },
            /*delete category*/
            onClickDeleteCategory() {
                this.deleteParams.active = true;
            },
            cancelDelete() {
                this.deleteParams.active = false;
            },
            confirmDelete() {
                this.deleteParams.active = false;
                this.deleteCategory()
            },
            deleteCategory() {
                this.isDeletingCategory = true;
                axios.delete(this.category.urls.delete).then(response => {
                    this.isDeletingCategory = false;
                    if (response.data.status == true) {
                        this.loadUser();
                        this.$emit('reload-categories');
                    }
                }).catch(error => {
                    this.isDeletingCategory = false;
                })
            },
            loadUser() {
                this.$store.dispatch(LOAD_USER);
            },
            onClickEditCategory() {
                this.editingCategoryName = true;
            }
            emitReloadCategory() {
                this.$emit('reload-category', this.category);
            }
        },
        computed: {
            category(){
                return this.currentCategory;
            },
            loadProductsRequestData(){
                return {
                    params: {
                        offset: this.products.length,
                        length: this.productLength,
                        category_id: this.category.id,
                        key: this.productSearchTerm,
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
            isCollapsed(){
                return this.$store.getters.categoriesCollapsed[this.category.id];
            },
            productSearchTerm(){
                return this.$store.getters.productSearchTerm;
            },
            categorySearchPromise(){
                return this.$store.getters.categorySearchPromise;
            },
            moreProductsToLoad(){
                return this.products.length < this.category.numberOfProducts;
            }
        }
    }
</script>

<style>
    .category-th {
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

    .cell-category-collapse {
        background-color: #d3d3d3;
        cursor: pointer;
    }

    .btn-category-collapse {
        font-size: 35px;
    }

    .btn-collapse.collapsed i.fa-angle-up {
        transform: rotate(-180deg);
        -moz-transform: rotate(-180deg);
        -ms-transform: rotate(-180deg);
        -o-transform: rotate(-180deg);
        -webkit-transform: rotate(-180deg);
        transition: transform 550ms ease;
        -moz-transition: -moz-transform 550ms ease;
        -ms-transition: -ms-transform 550ms ease;
        -o-transition: -o-transform 550ms ease;
        -webkit-transition: -webkit-transform 550ms ease;
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

    .lnk-load-more {
        text-transform: uppercase;
    }
</style>