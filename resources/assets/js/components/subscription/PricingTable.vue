<template>
    <div class="row p-t-50">
        <single-subscription-plan class="col-md-4 plan" v-for="productFamily in productFamilies" :single-product="productFamily.product" :preview="productFamily.preview" @select-subscription-plan="emitSelectedSubscriptionPlan">
        </single-subscription-plan>
    </div>
</template>

<script>
    import singleSubscriptionPlan from './SingleSubscriptionPlan.vue';

    export default {
        components: {
            singleSubscriptionPlan
        },
        data: () => {
            return {
                productFamilies: [],
            }
        },
        methods: {
            loadProducts: function () {
                axios.get('/subscription/product').then(response => {
                    console.info('response', response);
                    this.productFamilies = response.data.productFamilies;
                }).catch(error => {
                    if (error.response && error.response.status === 422 && error.response.data) {
                        console.info(error.response);
                        this.errors = error.response.data;
                    }
                })
            },
            emitSelectedSubscriptionPlan: function (product) {
                this.$emit('select-subscription-plan', product);
            }
        },
        mounted() {
            console.log('Pricing Table component mounted.');
            this.loadProducts();
        },
    }
</script>