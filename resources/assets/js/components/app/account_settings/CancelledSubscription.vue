<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    <h4>Subscription has been cancelled</h4>
                    <p>You have cancelled the subscription of <strong>{{ product.name }}</strong>.</p>
                    <p>We would love to know what made you want to cancel. Please
                        <a href="https://www.surveymonkey.com/r/7QCRNHY" target="_blank">send us your feedback</a>!
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'subscription',
            'subscriptionPlan'
        ],
        mounted(){
            console.info('CancelledSubscription component mounted.');
        },
        computed: {
            product(){
                return this.subscriptionPlan;
            },

        }
    }
</script>

<style>

</style>