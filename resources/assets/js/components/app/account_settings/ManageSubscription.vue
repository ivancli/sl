<template>
    <div class="row">
        <div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10">
            <div class="p-10">
                <div class="row subscription-info-panel" v-if="subscriptionPlan != null">
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
                                <a href="#" class="btn btn-primary btn-flat">
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
                            <tr>
                                <td colspan="4" align="center">No payment histories in the list</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <a href="https://gmail-sandbox.chargify.com/update_payment/16543488/a87d8ff5bc"
                                   class="btn btn-primary btn-flat">
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
</template>
<script>
    import formatDateTime from '../../../filters/formatDateTime';

    export default{
        data(){
            return {
                subscriptionPlan: null,
                updatePaymentProfileLink: '',
                transactions: [],
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
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
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