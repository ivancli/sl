<template>
    <li>
        <div class="checkbox product-checkbox">
            <label>
                <input type="checkbox" v-model="isSelected">
                &nbsp;
                {{ product.product_name }}
            </label>
        </div>
        &nbsp;&nbsp;
        <div class="product-option" v-if="isSelected">
            <div class="inline-block">
                <select class="input-sm form-control sl-form-control" v-model="type">
                    <option value="">-- select alert type --</option>
                    <option value="my_price">beats my price</option>
                    <option value="price_change">price changes</option>
                    <option value="custom">equal or below a specific price</option>
                </select>
            </div>
            &nbsp;&nbsp;
            <div class="inline-block" v-if="showSpecificPriceInput">
                $&nbsp;<input type="text" placeholder="enter a price" class="product-comp-price form-control sl-form-control input-sm p-l-5" v-model="price">
            </div>
        </div>
    </li>
</template>

<script>
    export default{
        props: [
            'current-product'
        ],
        mounted(){
            console.info('SingleProduct component mounted.');
        },
        watch: {
            isSelected(val){
                if(val == false){
                    this.type = null;
                }
                this.emitOptionChanged();
            },
            type(val){
                if (val != 'custom') {
                    this.price = null;
                }
                this.emitOptionChanged();
            },
            price(){
                this.emitOptionChanged();
            }
        },
        data(){
            return {
                isSelected: false,
                type: "",
                price: null,
            }
        },
        methods: {
            emitOptionChanged(){
                this.$emit('option-changed', this.alert);
            }
        },
        computed: {
            product(){
                return this.currentProduct;
            },
            showSpecificPriceInput(){
                return this.type == 'custom';
            },
            alert(){
                return {
                    product_id: this.product.id,
                    type: this.type,
                    price: this.price
                }
            }
        }
    }
</script>

<style>
    .product-checkbox, .product-option {
        display: inline-block;
    }
</style>