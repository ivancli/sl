<template>
    <div>
        <div class="registration-form">
            <p class="register-box-msg">Sign up now</p>
            <ul class="text-danger errors-container">
            </ul>

            <form method="POST" action="http://login.spotlite.com.au/register" accept-charset="UTF-8" id="frm-register" onsubmit="return validateRegistrationForm();"><input name="_token" type="hidden" value="3i7c4iYsz2Huf5ayxH60fmJl1iLS6F6otURhOKYM">
                <div class="form-group has-feedback">
                    <select class="form-control" name="title"><option selected="selected" value="">Title</option><option value="Ms">Ms</option><option value="Mrs">Mrs</option><option value="Miss">Miss</option><option value="Mr">Mr</option></select>
                </div>
                <div class="form-group required">
                    <input class="form-control" placeholder="First name" name="first_name" type="text">
                </div>
                <div class="form-group required">
                    <input class="form-control" placeholder="Last name" name="last_name" type="text">
                </div>
                <div class="form-group has-feedback required">
                    <input class="form-control" placeholder="Email" name="email" type="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback required">
                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback required">
                    <input class="form-control" placeholder="Confirm password" name="password_confirmation" type="password" value="">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Coupon code" name="coupon_code" type="text">
                </div>                <input type="hidden" name="signup_link" id="txt-signup-link" value="https://spotlite.chargify.com/subscribe/wymrvs7y6gt4/professional">
                <input type="hidden" name="api_product_id" id="txt-api-product-id" value="4155068">
                <div class="row">
                    <div class="col-sm-7">
                        <div class="checkbox icheck">
                            <label>
                                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" value="y" name="agree_terms" id="chk-agree-terms" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> &nbsp; I agree
                                to the <a href="#" onclick="showTerms(); return false;">terms</a>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <input class="btn btn-primary btn-block btn-flat" id="btn-register" type="submit" value="SIGN UP NOW">
                    </div>
                </div>
            </form>

            <a href="http://login.spotlite.com.au/login" class="text-center">I already have a subscription</a>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Register component mounted.')
        }
    }
</script>