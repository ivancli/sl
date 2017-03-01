<template>
    <div>
        <div :class="product.criteria.recommended ? 'recommend-outer' : ''">
            <div class="trapezoid" v-if="product.criteria.recommended">
                <span>Recommended</span>
            </div>
            <div class="pricing-level" :class="product.criteria.recommended ? 'recommended' : ''">
                <header>
                    <p class="lead-text" v-text="product.name"></p>
                    <p class="price-month">
                        ${{ preview.next_billing_manifest.total_in_cents/100 | currency }}<span>/{{product.interval_unit}}</span>
                    </p>
                </header>
                <div class="pricing-body">
                    <ul>
                        <li v-if="product.criteria.recommended > 0">
                            Up to <strong>{{product.criteria.product}} Products</strong>
                        </li>
                        <li v-else>
                            <strong>Unlimited Products</strong>
                        </li>

                        <li v-if="product.criteria.site > 0">
                            Up to <strong>{{product.criteria.site}} Competitors</strong> per product
                        </li>
                        <li v-else>
                            <strong>Unlimited Competitors</strong> Tracking
                        </li>
                        <li v-if="product.criteria.dashboard">
                            Customisable Dashboard
                        </li>
                        <li>
                            <strong v-text="product.criteria.alert_report == 'basic' ? 'Basic' : 'Advanced' "></strong>
                            Alerts and Reports
                        </li>
                        <li>
                            Updates
                            <strong v-if="product.criteria.frequency == 24">Every Day</strong>
                            <strong v-else-if="product.criteria.frequency == 1">Every Hour</strong>
                            <strong v-else>Every {{product.criteria.frequency}} Hours</strong>
                        </li>
                        <li>
                            <!--TODO not yet finished-->
                            <strong v-if="product.criteria.historic_pricing == 1">1 Month</strong>
                            <strong v-else-if="product.criteria.historic_pricing == 0">Lifetime</strong>
                            <strong v-else>{{product.criteria.historic_pricing}} Months</strong>
                            Historic Pricing
                        </li>
                        <li v-if="product.criteria.my_price == true">
                            <strong>"My Price" Nomination</strong>
                        </li>
                    </ul>
                </div>
                <footer><p class="text-center">
                    <a href="#" v-if="subscribedSubscriptionPlanId && subscribedSubscriptionPlanId == product.id"
                       class="button button-blue disabled subscribed">Subscribed</a>
                    <a href="#" v-else class="button button-blue"
                       :title="buttonTitle" :disabled="isDisabled" :class="buttonClass"
                       @click.prevent="selectSubscriptionPlan(product.id)" v-text="buttonText"></a>
                </p>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    import currency from '../../filters/currency';
    import {
        SET_SUBSCRIPTION_PLAN_ID
    } from '../../actions/action-types';

    export default{
        props: [
            'singleProduct',
            'preview',
            'loginUser',
        ],
        mounted(){
            console.info("SingleSubscriptionPlan component loaded.");
        },
        methods: {
            selectSubscriptionPlan: function (selectedSubscriptionPlanId) {
                this.$store.dispatch(SET_SUBSCRIPTION_PLAN_ID, {
                    selectedSubscriptionPlanId
                });
                this.$emit('select-subscription-plan', this.product);
            },
        },
        computed: {
            user(){
                return this.loginUser;
            },
            product(){
                if (typeof this.user != 'undefined') {
                    delete this.singleProduct.criteria.recommended;
                }
                return this.singleProduct;
            },
            selectedSubscriptionPlanId(){
                return this.$store.getters.selectedSubscriptionPlanId;
            },
            subscribedSubscriptionPlanId(){
                if (typeof this.user != 'undefined') {
                    if (user.subscription && user.subscription.apiSubscription) {
                        return user.subscription.apiSubscription.product_id;
                    }
                }
                return null;
            },
            isDisabled(){
                if (this.selectedSubscriptionPlanId == this.product.id) {
                    return true;
                }
                if (typeof this.user != 'undefined') {
                    if (user.numberOfProducts > this.product.criteria.product) {
                        return true;
                    }
                    if (user.maxNumberOfSites > this.product.criteria.site) {
                        return true;
                    }
                }
                return false;
            },
            buttonText(){
                if (this.selectedSubscriptionPlanId == this.product.id) {
                    return 'Selected'
                }
                if (typeof this.user != 'undefined') {
                    if (user.numberOfProducts > this.product.criteria.product) {
                        return 'Not Available';
                    }
                    if (user.maxNumberOfSites > this.product.criteria.site) {
                        return 'Not Available';
                    }
                    if (user.subscription && user.subscription.apiSubscription) {
                        if (user.subscription.apiSubscription.product_price_in_cents > this.product.price_in_cents) {
                            return 'Downgrade';
                        } else if (user.subscription.apiSubscription.product_price_in_cents < this.product.price_in_cents) {
                            return 'Upgrade';
                        }
                    }
                }
                return 'Get ' + this.product.name + ' plan'
            },
            buttonTitle(){
                if (this.selectedSubscriptionPlanId == this.product.id) {
                    return 'You have selected this subscription plan';
                }
                if (typeof this.user != 'undefined') {
                    if (user.numberOfProducts > this.product.criteria.product) {
                        return 'Your account has more products than this subscription plan allows.';
                    }
                    if (user.maxNumberOfSites > this.product.criteria.site) {
                        return 'Your account has more sites than this subscription plan allows.';
                    }
                }
                return null;
            },
            buttonClass(){
                if (this.selectedSubscriptionPlanId == this.product.id) {
                    return 'disabled';
                }
                if (typeof this.user != 'undefined') {
                    if (user.numberOfProducts > this.product.criteria.product) {
                        return 'unable';
                    }
                    if (user.maxNumberOfSites > this.product.criteria.site) {
                        return 'unable';
                    }
                }
                return '';
            }
        }
    }

</script>

<style>

</style>