<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="row m-b-20 p-10" v-show="migratingSubscriptionPlan">
                <div class="col-sm-12">
                    <pricing-table @select-subscription-plan="selectSubscriptionPlan"></pricing-table>
                </div>
                <div class="col-sm-12 text-center">
                    <button class="btn btn-default btn-flat" @click.prevent="cancelMigratingSubscriptionPlan">BACK
                    </button>
                </div>
            </div>
            <div class="row" v-show="!migratingSubscriptionPlan" v-if="subscriptionPlan != null">
                <div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10">
                    <div class="p-10">
                        <div class="row subscription-info-panel">
                            <div class="col-sm-12">
                                <div class="row m-b-20">
                                    <div class="col-sm-12" v-if="successMsg.length > 0">
                                        <div class="text-success" v-text="successMsg">

                                        </div>
                                    </div>
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
                                        <strong>{{ subscription.next_assessment_at | formatDateTime(dateFormat)
                                            }}</strong>
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
                    </div>
                </div>
            </div>
        </div>
        <confirm :content="confirmMigrateContent" :title="confirmMigrateTitle" @confirm="migrateSubscription"
                 @hide="cancelConfirmMigrate"></confirm>
        <loading v-show="submittingMigration"></loading>
    </div>
</template>
<script>
    import pricingTable from '../../subscription/PricingTable.vue';
    import confirm from '../../fragments/modals/Confirm.vue';
    import loading from '../../Loading.vue'

    import formatDateTime from '../../../filters/formatDateTime';
    import currency from '../../../filters/currency';

    import {
        SET_SUBSCRIPTION_PLAN_ID
    } from '../../../actions/action-types';

    export default{
        components: {
            pricingTable,
            confirm,
            loading
        },
        data(){
            return {
                subscriptionPlan: null,
                updatePaymentProfileLink: '',
                transactions: [],

                migratingSubscriptionPlan: false,
                submittingMigration: false,
                confirmMigrateTitle: "",
                confirmMigrateContent: "",
                successMsg: "",
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
                if (subscriptionPlan.price_in_cents > this.subscriptionPlan.price_in_cents) {
                    this.confirmMigrateTitle = "Upgrade Subscription";
                    this.confirmMigrateContent = "By upgrading your subscription you will be immediately charged the pro-rata amount for the rest of the month at the new subscription fee. <br><br>Are you sure you want to change your subscription?";
                } else if (subscriptionPlan.price_in_cents < this.subscriptionPlan.price_in_cents) {
                    this.confirmMigrateTitle = "Downgrade Subscription";
                    this.confirmMigrateContent = "By downgrading your subscription you will receive a credit for the pro-rata amount for the rest of the month at the next subscription fee. This credit will be offset against future subscription charges. <br><br>Are you sure you want to change your subscription?"
                } else {
                    this.confirmMigrateTitle = "";
                    this.confirmMigrateContent = ""
                }
                /*TODO if popup cancelled, reset selected subscription plan*/
            },
            resetSelectedSubscriptionPlan: function () {
                this.$store.dispatch(SET_SUBSCRIPTION_PLAN_ID, {
                    selectedSubscriptionPlanId: null
                });
            },
            migrateSubscription: function () {
                if (this.selectedSubscriptionPlanId != null) {
                    this.submittingMigration = true;
                    this.resetConfirmMigrateMessage();
                    axios.put('/subscription/subscription/' + user.subscription.id, this.migrateSubscriptionData).then(response => {
                        this.submittingMigration = false;
                        if (response.data.status == true) {
                            this.setSuccessMsg();
                        }
                    }).catch(error => {
                        this.submittingMigration = false;
                        if (error.response && error.response.status == 422 && error.response.data) {
                            this.errors = error.response.data;
                        }
                    })
                }
            },
            resetConfirmMigrateMessage: function(){
                this.confirmMigrateTitle = "";
                this.confirmMigrateContent = "";
            },
            cancelConfirmMigrate: function () {
                this.resetConfirmMigrateMessage();
                this.resetSelectedSubscriptionPlan();
            },
            setSuccessMsg: function () {

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
            },
            migrateSubscriptionData(){
                return {
                    product_id: this.selectedSubscriptionPlanId
                };
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