<template>
    <div>
        <div class="registration-form">
            <p class="register-box-msg">Sign up now</p>
            <ul class="text-danger errors-container">
            </ul>

            <form method="POST" action="/register" id="frm-register" @submit.prevent="submitRegister">
                <div class="form-group has-feedback">
                    <select class="form-control" name="title" v-model="title">
                        <option selected="selected" value="">Title</option>
                        <option value="Ms">Ms</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Miss">Miss</option>
                        <option value="Mr">Mr</option>
                    </select>
                </div>
                <div class="form-group required">
                    <input class="form-control" placeholder="First name" name="first_name" type="text"
                           v-model="first_name">
                </div>
                <div class="form-group required">
                    <input class="form-control" placeholder="Last name" name="last_name" type="text"
                           v-model="last_name">
                </div>
                <div class="form-group has-feedback required">
                    <input class="form-control" placeholder="Email" name="email" type="email" v-model="email_name">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback required">
                    <input class="form-control" placeholder="Password" name="password" type="password"
                           v-model="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback required">
                    <input class="form-control" placeholder="Confirm password" name="password_confirmation"
                           v-model="password_confirmation" type="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Coupon code" name="coupon_code" type="text">
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="y" name="agree_terms" id="chk-agree-terms">
                                &nbsp; I agree to the <a href="#">terms</a>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <input class="btn btn-primary btn-block btn-flat" type="submit" value="SIGN UP NOW">
                    </div>
                </div>
            </form>

            <a href="/login" class="text-center">I already have a subscription</a>
        </div>
    </div>
</template>

<script>
    export default {
        data: ()=> {
            return {
                title: '',
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                coupon_code: '',
                agree_terms: null
            }
        },
        methods: {
            submitRegister: function() {
                
            }
        },
        mounted() {
            console.log('Register component mounted.')
        }
    }
</script>