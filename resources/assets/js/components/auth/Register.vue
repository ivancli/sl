<template>
    <div v-if="selectedSubscriptionPlanId != null">
        <div class="login-box m-t-0 m-b-50">
            <div class="login-box-body">
                <div class="registration-form">
                    <p class="register-box-msg">Sign up now</p>
                    <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                        <li v-for="error in errors">
                            <div v-if="error.constructor != Array" v-text="error"></div>
                            <div v-else v-for="message in error" v-text="message"></div>
                        </li>
                    </ul>
                    <form method="POST" action="/register" id="frm-register" @submit.prevent="submitRegister">
                        <div class="form-group has-feedback">
                            <select class="form-control" name="title" v-model="title">
                                <option value="">Title</option>
                                <option value="Ms">Ms</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Mr">Mr</option>
                            </select>
                        </div>
                        <div class="form-group required">
                            <input class="form-control" placeholder="First name" name="first_name" type="text"
                                   v-model="firstName">
                        </div>
                        <div class="form-group required">
                            <input class="form-control" placeholder="Last name" name="last_name" type="text"
                                   v-model="lastName">
                        </div>
                        <div class="form-group has-feedback required">
                            <input class="form-control" placeholder="Email" name="email" type="email" v-model="email">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback required">
                            <input class="form-control" placeholder="Password" name="password" type="password"
                                   v-model="password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback required">
                            <input class="form-control" placeholder="Confirm password" name="password_confirmation"
                                   v-model="passwordConfirmation" type="password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Coupon code" name="coupon_code" type="text"
                                   v-model="couponCode">
                        </div>
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="y" name="agree_terms" id="chk-agree-terms"
                                               v-model="agreeTerms">
                                        &nbsp; I agree to the <a href="#">terms</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <input class="btn btn-primary btn-block btn-flat" type="submit"
                                       :disabled="isRegistering"
                                       :value="isRegistering ? 'SIGNING UP' : 'SIGN UP NOW'">
                            </div>
                        </div>
                    </form>

                    <a href="/login" class="text-center">I already have a subscription</a>
                </div>
            </div>
        </div>
        <loading v-if="isRegistering"></loading>
    </div>
</template>

<script>
    //    Vue.component('loading', require('../Loading.vue'));
    import loading from '../Loading.vue';

    export default {
        components: {
            loading
        },
        data: ()=> {
            return {
                isRegistering: false,
                title: '',
                firstName: '',
                lastName: '',
                email: '',
                password: '',
                passwordConfirmation: '',
                couponCode: '',
                agreeTerms: '',
                errors: {}
            }
        },
        methods: {
            submitRegister: function () {
                this.isRegistering = true;
                this.errors = {};
                axios.post('/register', this.registerData).then(response=> {
                    this.isRegistering = false;
                    if (response.data.redirect_path) {
                        window.location.href = response.data.redirect_path;
                    }
                }).catch(error=> {
                    this.isRegistering = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            registerData: function () {
                return {
                    title: this.title,
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
                    coupon_code: this.couponCode,
                    agree_terms: this.agreeTerms == true ? 'y' : '',
                    subscription_plan_id: this.selectedSubscriptionPlanId,
                }
            },
            selectedSubscriptionPlanId(){
                return this.$store.state.selectedSubscriptionPlanId;
            }
        },
        mounted() {
            console.log('Register component mounted.')
        }
    }
</script>