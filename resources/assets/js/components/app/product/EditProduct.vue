<template>
    <div class="edit-category-wrapper">
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
            this.newProductName = this.currentProduct.product_name;
            console.info('Edit Product component mounted');
        },
        watch:{
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
            cancelEditProductName: function () {
                this.newProductName = this.currentProduct.product_name;
                this.editingProductAttributes = false;
                this.$refs['txt_edit_product'].blur();
                this.$emit('cancel-edit-product-name');
            },
            editProduct: function () {
                // product name same as before, quit
                if (this.currentProduct.product_name == this.newProductName) {
                    this.isEditingProduct = false;
                    this.editingProductAttributes = false;
                    this.$refs['txt_edit_product'].blur();
                    this.$emit('cancel-edit-product-name');
                    return false;
                }

                this.isEditingProduct = true;
                this.clearErrors();
                axios.put('/product/' + this.currentProduct.id, this.editProductData).then(response=> {
                    this.isEditingProduct = false;
                    if (response.data.status == true) {
                        this.editingProductAttributes = false;
                    }
                    this.$emit('edited-product');
                }).catch(error=> {
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
                };
            }
        }
    }
</script>

<style>
    .edit-product-wrapper {
        display: inline-block;
    }

    .btn-edit.btn-edit-product {
        margin-left: 30px;
        color: #aaa;
        font-size: 12px;
        cursor: pointer;
    }

</style>