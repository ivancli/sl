<template>
    <div>
        <p class="login-box-msg">Sign in to start your session</p>
        <ul class="text-danger errors-container">
        </ul>
        <form method="POST" action="http://login.spotlite.com.au/login" accept-charset="UTF-8" id="frm-login"
              onsubmit="gaLogin();showLoading();"><input name="_token" type="hidden"
                                                         value="sEJRLjqHE1eSRGTL19RNWLBanNj3iyOpqcCM8QAo">
            <div class="form-group has-feedback">
                <input class="form-control" placeholder="Email" autocomplete="off" name="email" type="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" value="1" name="remember" id="remember"> &nbsp; Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">SIGN IN</button>
                </div>
            </div>
        </form>
        <div style="margin-bottom: 10px;"></div>
        <a href="http://login.spotlite.com.au/password" onclick="gaForgotPassword();">I forgot my password</a><br>
        <a href="http://www.spotlite.com.au/#scroll-pricing" onclick="gaRegister();" class="text-center">New to
            SpotLite? Sign up now!</a>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Login component mounted.')
        }
    }
</script>