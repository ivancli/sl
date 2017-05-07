<template>
    <div class="add-item-block add-product-container">
        <div class="add-item-label" v-show="!addingProduct" @click.prevent="goingToAddProduct">
            <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;
            <span class="add-item-text">ADD PRODUCT</span>
        </div>
        <div class="add-item-controls" v-show="addingProduct && !reachedProductLimit">
            <div class="row">
                <div class="col-sm-12">
                    <form class="form-horizontal form-sl-horizontal">
                        <input type="text" autocomplete="off" class="form-control txt-item txt-product-name" ref="txt_new_product" tabindex="=-1" v-model="newProductName"
                               placeholder="Enter a product name">

                        <div class="form-group">
                            <label class="control-label col-sm-3">Brand</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control txt-product-meta" v-model="brand">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Supplier</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control txt-product-meta" v-model="supplier">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">SKU</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control txt-product-meta" v-model="sku">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Cost price</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control txt-product-meta" v-model="costPrice">
                            </div>
                        </div>


                        <div class="buttons text-right">
                            <button class="btn btn-primary btn-flat btn-sm" @click.prevent="addProduct">
                                <span class="hidden-sm hidden-xs">
                                    CONFIRM
                                </span>
                                <span class="visible-sm visible-xs">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </button>
                            &nbsp;&nbsp;
                            <button class="btn btn-default btn-flat btn-cancel-add-product btn-sm" @click.prevent="cancelAddProduct">
                                <span class="hidden-sm hidden-xs">
                                    CANCEL
                                </span>
                                <span class="visible-sm visible-xs">
                                    <i class="fa fa-times"></i>
                                </span>
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
        <div class="add-item-controls" v-show="addingProduct && reachedProductLimit">
            <div class="subscription-upgrade-message">
                You have reached the product limit of {{ subscriptionPlanName }} plan.<br/>
                Please <a href="#">upgrade your subscription</a> to add more products.
            </div>
        </div>
        <error-modal :modal-errors="errors" @hideErrorModal="clearErrors"></error-modal>
    </div>
</template>

<script>
    import errorModal from '../../fragments/modals/Error.vue';

    export default {
        props: [
            'category'
        ],
        components: {
            errorModal
        },
        mounted(){
            console.info('AddProduct component is mounted');
        },
        data() {
            return {
                addingProduct: false, //determine visibility of panel
                newProductName: '', //product name input value
                brand: '',
                supplier: '',
                sku: '',
                costPrice: '',
                isAddingProduct: false, //promise of form submission,
                errors: {},
            }
        },
        methods: {
            goingToAddProduct: function () {
                this.addingProduct = true;
                setTimeout(() => {
                    this.$refs['txt_new_product'].focus();
                }, 10)
            },
            cancelAddProduct: function () {
                this.addingProduct = false;
                this.clearNewProductForm();
                this.$refs['txt_new_product'].blur();
            },
            addProduct: function () {
                this.$refs['txt_new_product'].blur();
                this.isAddingProduct = true;
                this.errors = {};
                axios.post('/product', this.addProductData).then(response => {
                    this.isAddingProduct = false;
                    if (response.data.status == true) {
                        this.addingProduct = false;
                        this.clearNewProductForm();
                        this.$emit('added-product', response.data.product);
                    }
                }).catch(error => {
                    this.isAddingProduct = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            },
            clearNewProductForm: function () {
                this.newProductName = '';
                this.brand = '';
                this.supplier = '';
                this.sku = '';
                this.costPrice = '';
            }
        },
        computed: {
            addProductData: function () {
                return {
                    category_id: this.category.id,
                    product_name: this.newProductName,
                    meta: {
                        brand: this.brand,
                        supplier: this.supplier,
                        sku: this.sku,
                        cost_price: this.costPrice,
                    },
                };
            },
            user(){
                return this.$store.getters.user
            },
            numberOfProducts(){
                return this.user.numberOfProducts;
            },
            maxNumberOfProducts(){
                return this.user.maxNumberOfProducts;
            },
            reachedProductLimit(){
                if (this.maxNumberOfProducts != null && this.numberOfProducts >= this.maxNumberOfProducts) {
                    return true;
                } else {
                    return false;
                }
            },
            subscription(){
                if (this.user.hasOwnProperty('subscription')) {
                    return this.user.subscription
                } else {
                    return null;
                }
            },
            subscriptionPlan(){
                if (this.subscription != null) {
                    return this.subscription.subscriptionPlan
                } else {
                    return null;
                }
            },
            subscriptionPlanName(){
                if (this.subscriptionPlan != null) {
                    return this.subscriptionPlan.name;
                } else {
                    return null;
                }
            },
        }
    }
</script>

<style>
    .add-product-container {
        border: 2px dashed lightgrey;
        border-radius: 5px;
        margin-top: 10px;
        margin-bottom: 10px;
        color: #777;
        font-weight: bold;
        cursor: pointer;
        width: 415px;
        max-width: 100%;
    }

    .add-product-container .txt-item.txt-product-name {
        border: none;
        border-bottom: 1px solid #7ed0c0;
        padding-left: 0px;
        padding-right: 0px;
        padding-bottom: 15px;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .add-product-container .txt-product-meta {
        border: none;
        border-bottom: 1px solid #73d0c0;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        font-size: 15px;
        font-weight: normal;
        padding-left: 0px;
        padding-right: 0px;
    }

    .add-product-container .add-item-label, .add-product-container .upgrade-for-add-item-controls {
        padding: 19px 19px 19px 50px;
    }

    .add-product-container i.label-icon {
        font-size: 25px;
        vertical-align: middle;
    }

    .add-product-container span.add-item-text {
        font-size: 14px;
        vertical-align: baseline;
    }

    .add-product-container .add-item-controls {
        background-color: #fff;
        padding: 13px;
    }

    @media (max-width: 991px) {
        .add-product-container .add-item-controls input {
            padding-right: 100px;
        }
    }

    @media (min-width: 768px) {
        .add-product-container .add-item-controls {
            padding-left: 25px;
            padding-right: 25px;
        }
    }
</style>