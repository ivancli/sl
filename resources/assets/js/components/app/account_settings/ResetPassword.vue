<template>

    <div class="row">
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-1 col-md-10">
            <div class="p-10">

                <h4 class="lead">Reset Password</h4>
                <hr>
                <p class="m-b-20">
                    By clicking the reset password button, an email with update password link will be sent
                    to <a :href="'mailto:'+email" v-text="email"></a>. Click on the link to set a
                    new password and confirm. Your password will be automatically updated.
                </p>
                <form @submit.prevent="resetPassword">
                    <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                        <li v-for="error in errors">
                            <div v-if="error.constructor != Array" v-text="error"></div>
                            <div v-else v-for="message in error" v-text="message"></div>
                        </li>
                    </ul>
                    <ul class="p-b-10 p-l-20 success-container" v-if="successMsg != ''">
                        <li v-text="successMsg"></li>
                    </ul>
                    <!--<div class="row m-b-20">-->
                    <!--<div class="col-sm-12">-->
                    <!--<div class="g-recaptcha" data-sitekey="6LfOnRYUAAAAAIaH7rRtA3ZOHTtaQsCMfmGtQP29"></div>-->
                    <!--</div>-->
                    <!--</div>-->

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-flat" @click.prevent="resetPassword">RESET PASSWORD</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isForgetting"></loading>
    </div>
</template>
<script>
    import loading from '../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading,
        },
        data(){
            return {
                isForgetting: false,
                errors: {},
                successMsg: '',
            }
        },
        mounted(){
            console.info('ResetPassword component is mounted.');
        },
        methods: {
            resetPassword () {
                this.isForgetting = true;
                this.errors = {};
                axios.post('/forgot', this.forgotPasswordRequestData).then(response => {
                    this.isForgetting = false;
                    if (response.data.status === true) {
                        this.successMsg = 'An email with the reset password link has been sent to the provided email address.';
                    }
                }).catch(error => {
                    this.isForgetting = false;
                    this.email = '';
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            user(){
                return this.$store.getters.user;
            },
            email(){
                return this.user.email;
            },
            forgotPasswordRequestData(){
                return {
                    email: this.user.email,
                }
            }
        }
    }
</script>
<style>

</style>