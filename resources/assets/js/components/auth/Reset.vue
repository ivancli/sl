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
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Password" type="password" :disabled="isResetting" v-model="password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Confirm Password" type="password" :disabled="isResetting" v-model="password_confirmation">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-block btn-flat" @click.prevent="onClickResetPassword">UPDATE PASSWORD</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isResetting"></loading>
    </div>
</template>

<script>
    import loading from '../fragments/loading/Loading.vue';
    import queryParameters from '../../filters/queryParameters';

    export default {
        components:{
            loading,
        },
        mounted() {
            console.log('Forgot component mounted.');
            this.initSetParams();
        },
        data(){
            return {
                isResetting: false,
                email: '',
                errors: {},
                successMsg: '',
                password: '',
                password_confirmation: '',
            }
        },
        methods: {
            initSetParams(){
                this.token = token;
            },
            onClickResetPassword(){
                this.resetPassword();
            },
            resetPassword(){
                this.isResetting = true;
                this.errors = {};
                axios.post('/password', this.resetPasswordRequestData).then(response => {
                    this.isResetting = false;
                    this.email = '';
                    if (response.data.status === true) {
                        this.redirect();
                    }
                }).catch(error => {
                    this.isResetting = false;
                    this.email = '';
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            redirect(){
                window.location.href='/';
            }
        },
        computed: {
            resetPasswordRequestData(){
                return {
                    token: this.token,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
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