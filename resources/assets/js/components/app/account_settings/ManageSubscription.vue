<template>
    <div class="row">
        <div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10">
            <div class="p-10">
                <div class="row subscription-info-panel" v-if="subscriptionPlan != null" v-show="!migratingSubscriptionPlan">
                    <div class="col-sm-12">
                        <div class="row m-b-20">
                            <div class="col-sm-12">
                                <h4>My Plan</h4>
                                <p>{{ subscriptionPlan.name }}</p>
                                <p v-if="subscription.state == 'trialing'">
                                    Trial expiry:
                                    {{ subscription.trial_ended_at | formatDateTime(dateFormat) }}
                                </p>
                            </div>
                            <div class="col-sm-12">
                                <a href="#" class="btn btn-primary btn-flat" @click.prevent="viewMigration">
                                    CHANGE MY PLAN
                                </a>
                            </div>
                        </div>
                        <div class="row m-b-20">
                            <div class="col-sm-12">
                                <h4>Payment Details</h4>
                                Upcoming Invoice:
                                <strong>{{ subscription.next_assessment_at | formatDateTime(dateFormat) }}</strong>
                            </div>
                        </div>
                        <p>Payment History: </p>
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-inverse">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Payment</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="transactions.length == 0">
                                <td colspan="4" align="center">No payment histories in the list</td>
                            </tr>
                            <tr v-else v-for="transaction in filteredTransactions">
                                <td>{{ transaction.created_at | formatDateTime(dateFormat) }}</td>
                                <td></td>
                                <td v-text="transaction.kind"></td>
                                <td>${{ transaction.amount_in_cents/100 | currency }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <a :href="updatePaymentProfileLink" class="btn btn-primary btn-flat">
                                    UPDATE PAYMENT DETAILS
                                </a>
                                &nbsp;
                                <button class="btn btn-default btn-flat">
                                    CANCEL SUBSCRIPTION
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-b-20" v-show="migratingSubscriptionPlan">
                    <div class="col-sm-12">
                        <pricing-table @select-subscription-plan="selectSubscriptionPlan"></pricing-table>
                    </div>
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-default btn-flat" @click.prevent="cancelMigratingSubscriptionPlan">BACK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import pricingTable from '../../subscription/PricingTable.vue';

    import formatDateTime from '../../../filters/formatDateTime';
    import currency from '../../../filters/currency';

    import {
            SET_SUBSCRIPTION_PLAN_ID
    } from '../../../actions/action-types';

    export default{
        components: {
            pricingTable
        },
        data(){
            return {
                subscriptionPlan: null,
                updatePaymentProfileLink: '',
                transactions: [],

                migratingSubscriptionPlan: false,
            }
        },
        mounted(){
            console.info('ManageSubscription component is mounted.');
            this.loadSubscription();
        },
        methods: {
            loadSubscription: function () {
                axios.get('/subscription/subscription/' + user.subscription.id).then(response => {
                    if (response.data.status == true) {
                        this.subscriptionPlan = response.data.subscriptionPlan;
                        this.updatePaymentProfileLink = response.data.updatePaymentProfileLink;
                        this.transactions = response.data.transactions;
                    }
                }).catch(error => {

                })
            },
            viewMigration: function () {
                this.migratingSubscriptionPlan = true;
            },
            cancelMigratingSubscriptionPlan: function () {
                this.migratingSubscriptionPlan = false;
                this.resetSelectedSubscriptionPlan();
            },
            selectSubscriptionPlan: function (subscriptionPlan) {
                /*TODO show confirmation popup and migrate*/
                console.info("selected", subscriptionPlan);

                /*TODO if popup cancelled, reset selected subscription plan*/
            },
            resetSelectedSubscriptionPlan: function () {
                this.$store.dispatch(SET_SUBSCRIPTION_PLAN_ID, {
                    selectedSubscriptionPlanId: null
                });
            }
        },
        computed: {
            subscription(){
                if (user.subscription && user.subscription.apiSubscription) {
                    return user.subscription.apiSubscription;
                } else {
                    return null;
                }
            },
            filteredTransactions(){
                /*TODO need to filter these transactions*/
                return this.transactions;
            },
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
            },
            selectedSubscriptionPlanId(){
                return this.$store.getters.selectedSubscriptionPlanId;
            }
        }
    }
</script>
<style>
    .thead-inverse th {
        background-color: #7ed0c0;
    }

    .thead-inverse th {
        color: #fff;
    }
</style>