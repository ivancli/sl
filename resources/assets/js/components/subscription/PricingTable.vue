<template>
    <div class="row p-t-50">
        <div class="col-lg-3 col-md-6 plan" v-for="productFamily in productFamilies"
             :data-link="productFamily.product.public_signup_pages[0].url" :data-id="productFamily.product.id"
             :data-price="productFamily.preview.next_billing_manifest.total_in_cents">


            <div :class="productFamily.product.criteria.recommended ? 'recommend-outer' : ''">
                <div class="trapezoid" v-if="productFamily.product.criteria.recommended">
                    <span>Recommended</span>
                </div>
                <div class="pricing-level" :class="productFamily.product.criteria.recommended ? 'recommended' : ''">
                    <header>
                        <p class="lead-text" v-text="productFamily.product.name"></p>
                        <p class="price-month">${{currency(productFamily.preview.next_billing_manifest.total_in_cents/100)}}<span>/{{productFamily.product.interval_unit}}</span>
                        </p>
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
                                <strong v-if="productFamily.product.criteria.historic_pricing == 1">1 Month</strong>
                                <strong v-else-if="productFamily.product.criteria.historic_pricing == 0">Lifetime</strong>
                                <strong v-else>{{productFamily.product.criteria.historic_pricing}} Months</strong>
                                Historic Pricing
                            </li>
                            <li v-if="productFamily.product.criteria.my_price == true">
                                <strong>"My Price" Nomination</strong>
                            </li>
                        </ul>
                    </div>
                    <footer><p class="text-center">
                        <a href="#" class="button button-blue"
                           :disabled="selectedSubscriptionPlanId == productFamily.product.id"
                           :class="selectedSubscriptionPlanId == productFamily.product.id ? 'disabled' : ''"
                           @click.prevent="selectSubscriptionPlan(productFamily.product.id)"
                           v-text="selectedSubscriptionPlanId == productFamily.product.id ? 'Selected' : ('Get ' + productFamily.product.name + ' plan')"
                        ></a>
                    </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {
            SET_SUBSCRIPTION_PLAN_ID
    } from '../../actions/mutation-types';

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
            },
            selectSubscriptionPlan: function (selectedSubscriptionPlanId) {
                this.$store.commit(SET_SUBSCRIPTION_PLAN_ID, selectedSubscriptionPlanId);
            },
            currency: function (d, a, b, c) {
                a = isNaN(a = Math.abs(a)) ? 2 : a; b = void 0 == b ? "." : b; c = void 0 == c ? "," : c;
                var e = d < 0 ? "-" : "", f = String(parseInt(d = Math.abs(Number(d) || 0).toFixed(a))), g = (g = f.length) > 3 ? g % 3 : 0;
                return e + (g ? f.substr(0, g) + c : "") + f.substr(g).replace(/(\d{3})(?=\d)/g, "$1" + c) + (a ? b + Math.abs(d - f).toFixed(a).slice(2) : "")
            }
        },
        computed: {
            selectedSubscriptionPlanId(){
                return this.$store.state.selectedSubscriptionPlanId;
            }
        },
        mounted() {
            console.log('Pricing Table component mounted.');
            this.loadProducts();
        }
    }
</script>