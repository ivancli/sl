<template>
    <div class="edit-category-wrapper">
        <span class="btn-edit btn-edit-product" @click.prevent="goingToEditProductName" v-show="!editingProductName">
            <span class="hidden-xs hidden-sm">Edit &nbsp;</span>
            <i class="fa fa-pencil-square-o"></i>
        </span>

        <div class="input-group sl-input-group" v-show="editingProductName">
            <input type="text" placeholder="Product Name" autocomplete="off" v-model="newProductName" class="form-control sl-form-control product-name" ref="txt_edit_product" tabindex="-1">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-flat" @click.prevent="editProduct">
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn btn-default btn-flat" @click.prevent="cancelEditProductName">
                    <i class="fa fa-times"></i>
                </button>
            </span>
        </div>
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
            'editing-product'
        ],
        data() {
            return {
                editingProductName: false, //determine visibility of panel
                newProductName: '', //product name input value
                isEditingProduct: false, //promise of form submission,
                errors: {},
            }
        },
        mounted(){
            this.newProductName = this.currentProduct.name;
            console.info('Edit Product component mounted');
        },
        methods: {
            goingToEditProductName: function () {
                this.editingProductName = true;
                setTimeout(()=> {
                    this.$refs['txt_edit_product'].focus();
                }, 10);
                this.$emit('edit-product-name');
            },
            cancelEditProductName: function () {
                this.newProductName = this.currentProduct.name;
                this.editingProductName = false;
                this.$refs['txt_edit_product'].blur();
                this.$emit('cancel-edit-product-name');
            },
            editProduct: function () {
                // product name same as before, quit
                if (this.currentProduct.name == this.newProductName) {
                    this.isEditingProduct = false;
                    this.editingProductName = false;
                    this.$refs['txt_edit_product'].blur();
                    this.$emit('cancel-edit-product-name');
                    return false;
                }

                this.isEditingProduct = true;
                this.clearErrors();
                axios.put('/product/' + this.currentProduct.id, this.editProductData).then(response=> {
                    this.isEditingProduct = false;
                    if (response.data.status == true) {
                        this.editingProductName = false;
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
                    name: this.newProductName,
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