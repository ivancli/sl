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
                <form onsubmit="return false;">
                    <ul class="text-danger errors-container">
                    </ul>
                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <div class="g-recaptcha" data-sitekey="6LfOnRYUAAAAAIaH7rRtA3ZOHTtaQsCMfmGtQP29"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-flat" @click="resetPassword">RESET PASSWORD</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data(){
            return {
                email: user.email
            }
        },
        mounted(){
            console.info('ResetPassword component is mounted.');
        },
        methods: {
            resetPassword: function () {
                /*TODO tbf*/
            }
        }
    }
</script>
<style>

</style>