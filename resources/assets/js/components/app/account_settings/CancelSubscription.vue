<template>
    <div class="box box-danger">
        <div class="box-header with-border">
            <h4 class="box-title text-danger"><i class="fa fa-exclamation-triangle"></i> Cancel my Subscription?
            </h4>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <form>
                        <div class="row">
                            <div class="col-sm-12">
                                <p>You may choose to keep your profile and settings in SpotLite (recommended) or delete your profile and settings from SpotLite completely</p>

                                <div class="well">
                                    <h4>Option 1</h4>
                                    <p>I would like to cancel my subscription and keep my profile and settings stored in SpotLite.</p>
                                    <p>
                                        <small class="text-muted">You might want to use it again in the future so, by keeping your profile, you can use SpotLite until the end of the billing period and then simply reactivate your account to continue using SpotLite in the future.</small>
                                    </p>
                                    <p class="text-danger">
                                        Are you sure you want to cancel your subscription?
                                    </p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="keep_profile" value="1" v-model="keepProfile">
                                            Yes. I agree to cancel my subscription and keep my profile.
                                        </label>
                                    </div>
                                </div>

                                <div class="well">
                                    <h4>Option 2</h4>
                                    <p>I would like to cancel my subscription and delete my profile and settings from SpotLite.</p>
                                    <p>
                                        <small class="text-muted">If you choose to continue using SpotLite in the future, you will need to re-enter all your Categories, Products and Product Page URLs. Your subscription will be cancelled immediately and you will no longer be able to access and use SpotLite or any of its features.</small>
                                    </p>
                                    <p class="text-danger">Are you sure you want to cancel your subscription? This action cannot be undone.</p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="keep_profile" value="0" v-model="keepProfile">
                                            Yes. I agree to cancel my subscription and delete my profile.
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <button class="btn btn-default btn-flat" @click.prevent="cancelSubscription">CONFIRM CANCELLATION</button>

                                        <button class="btn btn-default btn-flat" @click.prevent="hideCancel">BACK</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <loading v-if="isCancellingSubscription"></loading>
    </div>
</template>

<script>
    import loading from '../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading,
        },
        props: [
            'subscription',
        ],
        data(){
            return {
                keepProfile: '1',
                isCancellingSubscription: false,
            };
        },
        mounted(){
            console.info("CancelSubscription component mounted.");
        },
        methods: {
            cancelSubscription(){
                this.isCancellingSubscription = true;
                axios.delete(this.subscription.urls.delete, this.cancelSubscriptionRequestData).then(response => {
                    this.isCancellingSubscription = false;
                    if (response.data.status === true) {
                        this.emitCancelledSubscription();
                    }
                }).catch(error => {
                    this.isCancellingSubscription = false;
                    console.info(error);
                })
            },
            hideCancel(){
                this.emitHideCancel();
            },
            emitCancelledSubscription(){
                this.$emit('cancelled-subscription');
            },
            emitHideCancel(){
                this.$emit('hide-cancel');
            },
        },
        computed: {
            cancelSubscriptionRequestData(){
                return {
                    params: {
                        keep_profile: this.keepProfile === "1",
                    }
                };
            },
        }
    }
</script>

<style>

</style>