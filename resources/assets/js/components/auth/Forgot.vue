<template>
    <div>
        <div class="login-box m-t-0 m-b-50">
            <div class="login-box-body">
                <p class="login-box-msg">Enter your email to reset password</p>
                <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                    <li v-for="error in errors">
                        <div v-if="error.constructor != Array" v-text="error"></div>
                        <div v-else v-for="message in error" v-text="message"></div>
                    </li>
                </ul>
                <ul class="p-b-10 p-l-20 success-container" v-if="successMsg != ''">
                    <li v-text="successMsg"></li>
                </ul>
                <form id="frm-password">
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Email" autocomplete="off" type="email" v-model="email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-push-6 text-right">
                            <button class="btn btn-default btn-block btn-flat" @click.prevent="onClickResetPassword">RESET</button>
                        </div>
                        <div class="col-sm-6 col-sm-pull-6">
                            <div class="p-t-5 p-b-5">
                                <a href="/login">Back to login page</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isForgetting"></loading>
    </div>
</template>

<script>
    import loading from '../fragments/loading/Loading.vue';

    export default {
        components: {
            loading,
        },
        mounted() {
            console.log('Forgot component mounted.')
        },
        data(){
            return {
                isForgetting: false,
                email: '',
                errors: {},
                successMsg: '',
            }
        },
        methods: {
            onClickResetPassword(){
                this.forgotPassword();
            },
            forgotPassword(){
                this.isForgetting = true;
                this.errors = {};
                axios.post('/forgot', this.forgotPasswordRequestData).then(response => {
                    this.isForgetting = false;
                    this.email = '';
                    if (response.data.status === true) {
                        this.successMsg = 'An email with the reset password link has been sent to the provided email address.';
                    }
                }).catch(error => {
                    this.isForgetting = false;
                    this.email = '';
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            forgotPasswordRequestData(){
                return {
                    email: this.email,
                }
            },
        }
    }
</script>

<style>
    .success-container {
        color: #439c8b;
    }
</style>