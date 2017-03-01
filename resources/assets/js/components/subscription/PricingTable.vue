<template>
    <div class="row p-t-50">
        <single-subscription-plan class="col-lg-3 col-md-6 plan" v-for="productFamily in productFamilies"
                                  :single-product="productFamily.product" :preview="productFamily.preview"
                                  @select-subscription-plan="emitSelectedSubscriptionPlan"
                                  :login-user="user"
        >

        </single-subscription-plan>
    </div>
</template>

<script>
    import singleSubscriptionPlan from './SingleSubscriptionPlan.vue';

    export default {
        props: [
            'loginUser'
        ],
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
                    this.productFamilies = response.data;
                }).catch(error => {
                    if (error.response && error.response.status == 422 && error.response.data) {
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
        computed: {
            user(){
                return this.loginUser;
            }
        }
    }
</script>