<template>
    <div class="edit-product-wrapper" v-show="goingToEditProduct">
        <form class="form-horizontal form-sl-horizontal edit-item-controls">
            <input type="text" autocomplete="off" class="form-control txt-item txt-product-name" ref="txt_edit_product" tabindex="=-1" v-model="newProductName"
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
                <button class="btn btn-primary btn-flat btn-sm" @click.prevent="editProduct">
                    <span class="hidden-sm hidden-xs">
                        CONFIRM
                    </span>
                    <span class="visible-sm visible-xs">
                        <i class="fa fa-plus"></i>
                    </span>
                </button>
                &nbsp;&nbsp;
                <button class="btn btn-default btn-flat btn-cancel-edit-product btn-sm" @click.prevent="cancelEditProduct">
                    <span class="hidden-sm hidden-xs">
                        CANCEL
                    </span>
                    <span class="visible-sm visible-xs">
                        <i class="fa fa-times"></i>
                    </span>
                </button>
            </div>
        </form>
        <error-modal :modal-errors="errors" @hideErrorModal="clearErrors"></error-modal>
    </div>
</template>

<script>
    import errorModal from '../../fragments/modals/Error.vue';

    export default{
        components: {
            errorModal
        },
        props: [
            'editing-product',
            'going-to-edit-product',
        ],
        data() {
            return {
                editingProductAttributes: false, //determine visibility of panel
                newProductName: '', //product name input value
                brand: '',
                supplier: '',
                sku: '',
                costPrice: '',
                isEditingProduct: false, //promise of form submission,
                errors: {},
            }
        },
        mounted(){
            this.initProduct();
            console.info('Edit Product component mounted');
        },
        watch: {
            goingToEditProduct(val){
                this.editingProductAttributes = val;
                if (val == true) {
                    this.$refs['txt_edit_product'].focus();
                } else {
                    this.$refs['txt_edit_product'].blur();
                }
            }
        },
        methods: {
            initProduct(){
                this.newProductName = this.editingProduct.product_name;
                if (this.editingProduct.meta != null) {
                    this.brand = this.editingProduct.meta.brand;
                    this.supplier = this.editingProduct.meta.supplier;
                    this.sku = this.editingProduct.meta.sku;
                    this.costPrice = this.editingProduct.meta.cost_price;
                }
            },
            cancelEditProduct: function () {
                this.initProduct();
                this.editingProductAttributes = false;
                this.$refs['txt_edit_product'].blur();
                this.$emit('cancel-edit-product-name');
            },
            editProduct: function () {
                this.isEditingProduct = true;
                this.clearErrors();
                axios.put('/product/' + this.currentProduct.id, this.editProductData).then(response => {
                    this.isEditingProduct = false;
                    if (response.data.status == true) {
                        this.editingProductAttributes = false;
                    }
                    this.$emit('edited-product');
                }).catch(error => {
                    this.isEditingProduct = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            }
        },
        computed: {
            currentProduct: function () {
                return this.editingProduct;
            },
            editProductData: function () {
                return {
                    product_name: this.newProductName,
                    category_id: this.currentProduct.category_id,
                    meta: {
                        brand: this.brand,
                        supplier: this.supplier,
                        sku: this.sku,
                        cost_price: this.costPrice,
                    },
                };
            }
        }
    }
</script>

<style>
    .edit-product-wrapper {
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

    .edit-product-wrapper .txt-item.txt-product-name {
        border: none;
        border-bottom: 1px solid #7ed0c0;
        padding-left: 0px;
        padding-right: 0px;
        padding-bottom: 15px;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .edit-product-wrapper .txt-product-meta {
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

    .edit-product-wrapper .add-item-label, .edit-product-wrapper .upgrade-for-add-item-controls {
        padding: 19px 19px 19px 50px;
    }

    .edit-product-wrapper i.label-icon {
        font-size: 25px;
        vertical-align: middle;
    }

    .edit-product-wrapper span.add-item-text {
        font-size: 14px;
        vertical-align: baseline;
    }

    .edit-product-wrapper .edit-item-controls {
        background-color: #fff;
        padding: 13px;
    }

    @media (max-width: 991px) {
        .edit-product-wrapper .edit-item-controls input {
            padding-right: 100px;
        }
    }

    @media (min-width: 768px) {
        .edit-product-wrapper .edit-item-controls {
            padding-left: 25px;
            padding-right: 25px;
        }
    }

</style>