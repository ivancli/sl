<template>
    <div class=row>
        <div class="col-lg-3 col-md-6 plan" v-for="productFamily in productFamilies" :data-link="productFamily.product.public_signup_pages[0].url" :data-id="productFamily.product.id"
             :data-price="productFamily.preview.next_billing_manifest.total_in_cents">


            <div>
                <div class="trapezoid" v-if="productFamily.product.criteria.recommended">
                    <span>Recommended</span>
                </div>
                <div class="pricing-level">
                    <header>
                        <p class="lead-text" v-text="productFamily.product.name"></p>
                        <p class="price-month">{{productFamily.preview.next_billing_manifest.total_in_cents}}<span>/{{productFamily.product.interval_unit}}</span></p>
                    </header>
                    <div class="pricing-body">
                        <ul>
                            <li v-if="productFamily.product.criteria.recommended > 0">
                                Up to <strong>{{productFamily.product.criteria.product}} Products</strong>
                            </li>
                            <li v-else>
                                <strong>Unlimited Products</strong>
                            </li>

                            <li v-if="productFamily.product.criteria.site > 0">
                                Up to <strong>{{productFamily.product.criteria.site}} Competitors</strong> per product
                            </li>
                            <li v-else>
                                <strong>Unlimited Competitors</strong> Tracking
                            </li>
                            <li v-if="productFamily.product.criteria.dashboard">
                                Customisable Dashboard
                            </li>

                            <li>
                                <strong v-text="productFamily.product.criteria.alert_report == 'basic' ? 'Basic' : 'Advanced' "></strong>
                                Alerts and Reports
                            </li>

                            <li>
                                Updates
                                <strong v-if="productFamily.product.criteria.frequency == 24">Every Day</strong>
                                <strong v-else-if="productFamily.product.criteria.frequency == 1">Every Hour</strong>
                                <strong v-else>Every {{productFamily.product.criteria.frequency}} Hours</strong>
                            </li>
                            <li>
                                <!--TODO not yet finished-->
                                <strong v-if="historic_pricing">1 Month</strong>
                                Historic Pricing
                            </li>
                            <li style="visibility: hidden">
                                <strong>"My Price" Nomination</strong>
                            </li>
                        </ul>
                    </div>
                    <footer><p class="text-center">
                        <a href="#" class="button button-blue ">
                            Get Starter plan
                        </a>
                    </p>
                    </footer>
                </div>
            </div>

            <div>
                <div class="recommend-outer">
                    <div class="trapezoid">
                        <span>Recommended</span>
                    </div>
                    <div class="pricing-level
                                                                         recommended
                                                                                                                ">
                        <header><p class="lead-text">Professional</p>
                            <p class="price-month">
                                $99.00<span>/month</span>
                            </p>
                        </header>
                        <div class="pricing-body">
                            <ul>
                                <li>
                                    Up to
                                    <strong>100 Products</strong>
                                </li>

                                <li>
                                    Up to
                                    <strong>10 Competitors</strong>
                                    per product
                                    <span>per Product</span>
                                </li>

                                <li>
                                    Customisable Dashboard
                                </li>

                                <li>
                                    <strong>Advanced</strong> Alerts and Reports
                                </li>
                                <li>
                                    Updates
                                    <strong>Every 12 Hours</strong>
                                </li>
                                <li>
                                    <strong>12 Months</strong>
                                    Historic Pricing
                                </li>
                                <li>
                                    <strong>"My Price" Nomination</strong>
                                </li>
                            </ul>
                        </div>
                        <footer><p class="text-center">
                            <a href="#" class="button button-blue ">
                                Get Professional plan
                            </a>
                        </p>
                        </footer>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
    export default {
        data: ()=> {
            return {
                productFamilies: [],
            }
        },
        methods: {
            loadProducts: function () {
                axios.get('/subscription/product').then(response=> {
                    console.info('response', response);
                    this.productFamilies = response.data;
                }).catch(error=> {
                    if (error.response && error.response.status == 422 && error.response.data) {
                        console.info(error.response);
                        this.errors = error.response.data;
                    }
                })
            }
        },
        mounted() {
            console.log('Pricing Table component mounted.');
            this.loadProducts();
        }
    }
</script>