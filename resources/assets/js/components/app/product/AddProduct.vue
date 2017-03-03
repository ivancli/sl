<template>
    <div class="add-item-block add-product-container">
        <div class="add-item-label" v-show="!addingProduct" @click.prevent="goingToAddProduct">
            <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;
            <span class="add-item-text">ADD PRODUCT</span>
        </div>
        <div class="add-item-controls" v-show="addingProduct">
            <div class="row">
                <div class="col-sm-12">
                    <form>
                        <input type="text" autocomplete="off" class="form-control txt-item txt-product-name" ref="txt_new_product" tabindex="=-1" v-model="newProductName"
                               placeholder="Enter a product name here">
                        <div class="buttons">
                            <button class="btn btn-primary btn-flat" @click.prevent="addProduct">
                                <span class="hidden-sm hidden-xs">
                                    ADD PRODUCT
                                </span>
                                <span class="visible-sm visible-xs">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </button>
                            &nbsp;&nbsp;
                            <button class="btn btn-default btn-flat btn-cancel-add-product" @click.prevent="cancelAddProduct">
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
                isAddingProduct: false, //promise of form submission,
                errors: {},
            }
        },
        methods: {
            goingToAddProduct: function () {
                this.addingProduct = true;
                setTimeout(()=> {
                    this.$refs['txt_new_product'].focus();
                }, 10)
            },
            cancelAddProduct: function () {
                this.addingProduct = false;
                this.clearNewProductName();
                this.$refs['txt_new_product'].blur();
            },
            addProduct: function () {
                this.$refs['txt_new_product'].blur();
                this.isAddingProduct = true;
                this.errors = {};
                axios.post('/product', this.addProductData).then(response=> {
                    this.isAddingProduct = false;
                    if (response.data.status == true) {
                        this.addingProduct = false;
                    }
                    this.clearNewProductName();
                    this.$emit('added-product');
                }).catch(error=> {
                    this.isAddingProduct = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            },
            clearNewProductName: function () {
                this.newProductName = '';
            }
        },
        computed: {
            addProductData: function () {
                return {
                    product_name: this.newProductName,
                    category_id: this.category.id
                };
            }
        }
    }
</script>

<style>
    .add-item-block {
        border: 2px dashed lightgrey;
        border-radius: 5px;
        margin-top: 10px;
        margin-bottom: 10px;
        color: #777;
        font-weight: bold;
        height: 65px;
        cursor: pointer;
    }

    .add-item-block .add-item-label, .add-item-block .upgrade-for-add-item-controls {
        padding: 19px 19px 19px 50px;
    }

    .add-item-block i.label-icon {
        font-size: 25px;
        vertical-align: middle;
    }

    .add-item-block span.add-item-text {
        font-size: 14px;
        vertical-align: baseline;
    }

    .add-item-block .add-item-controls {
        background-color: #fff;
        padding: 13px;
    }

    .add-item-block .add-item-controls .buttons {
        position: absolute;
        right: 20px;
        top: 0;
    }

    .add-item-block .add-item-controls input {
        font-size: 18px;
        padding-right: 250px;
    }

    @media (max-width: 991px) {
        .add-item-block .add-item-controls input {
            padding-right: 100px;
        }
    }

    .add-item-block .txt-item {
        border: none;
    }

    @media (min-width: 768px) {
        .add-item-block .add-item-controls {
            padding-left: 50px;
        }
    }
</style>