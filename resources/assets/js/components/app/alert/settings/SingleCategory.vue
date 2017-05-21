<template>
    <li :class="categoryActiveClass">
        <div class="checkbox category-checkbox">
            <input type="checkbox" v-model="isSelected">
            &nbsp;
            <i class="category-collapse-indicator fa fa-play"></i>
            &nbsp;
            <span class="category-name" @click.prevent="toggleCategoryActive">{{ category.category_name }}</span>
            &nbsp;&nbsp;
        </div>
        <div class="category-option" v-show="isSelected">
            <select class="input-sm form-control sl-form-control" v-model="type">
                <option value="">-- select alert type --</option>
                <option value="my_price">beats my price</option>
                <option value="price_change">price changes</option>
            </select>
        </div>
        <div class="product-options p-l-30" v-show="isActive">
            <ul class="product-options-list">
                <single-product v-for="product in category.products" :current-product-alerts="currentProductAlerts" :current-product="product" @option-changed="updateProductAlerts"></single-product>
            </ul>
        </div>
    </li>
</template>

<script>
    import singleProduct from './SingleProduct.vue';

    export default{
        components: {
            singleProduct
        },
        props: [
            'currentCategory',
            'currentCategoryAlerts',
            'currentProductAlerts',
        ],
        mounted(){
            console.info('SingleCategory component mounted.');
            this.initSetCategoryAlert();
        },
        data(){
            return {
                isActive: false,
                isSelected: false,
                type: "",
                productAlerts: {},
            }
        },
        watch: {
            isSelected(val){
                if (val === false) {
                    this.type = '';
                }
                this.emitCategoryAlertUpdated();
            },
            type(){
                this.emitCategoryAlertUpdated();
            }
        },
        methods: {
            initSetCategoryAlert(){
                if (this.currentCategoryAlerts.hasOwnProperty(this.category.id)) {
                    let categoryAlert = this.currentCategoryAlerts[this.category.id];
                    this.isSelected = categoryAlert.is_selected;
                    this.type = categoryAlert.type;
                }
            },
            toggleCategoryActive(){
                this.isActive = !this.isActive;
            },
            updateProductAlerts(productAlert){
                this.productAlerts[productAlert.product_id] = productAlert;
                this.isActive = true;
                this.emitProductAlertsUpdated();
            },
            emitCategoryAlertUpdated(){
                this.$emit('category-alert-updated', this.categoryAlert);
            },
            emitProductAlertsUpdated(){
                this.$emit('product-alerts-updated', this.productAlerts);
            }
        },
        computed: {
            category(){
                return this.currentCategory;
            },
            categoryActiveClass(){
                if (this.isActive) {
                    return 'active';
                }
                return '';
            },
            categoryAlert(){
                return {
                    category_id: this.category.id,
                    is_selected: this.isSelected,
                    type: this.type,
                }
            }
        },
    }
</script>

<style>
    .category-checkbox, .category-option {
        display: inline-block;
    }

    .category-name {
        cursor: pointer;
    }

    .product-options-list {
        list-style: none;
        padding-left: 0;
    }

    .category-collapse-indicator {
        transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        transition: transform 550ms ease;
        -moz-transition: -moz-transform 550ms ease;
        -ms-transition: -ms-transform 550ms ease;
        -o-transition: -o-transform 550ms ease;
        -webkit-transition: -webkit-transform 550ms ease;
    }

    .category-options-list li.active .category-collapse-indicator {
        transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
        transition: transform 550ms ease;
        -moz-transition: -moz-transform 550ms ease;
        -ms-transition: -ms-transform 550ms ease;
        -o-transition: -o-transform 550ms ease;
        -webkit-transition: -webkit-transform 550ms ease;
        color: #696969;
    }
</style>