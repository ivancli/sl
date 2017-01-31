<template>
    <div>
        <p class="login-box-msg">Enter your email to reset password</p>
        <ul class="text-danger">
        </ul>
        <ul class="text-danger errors-container">
        </ul>
        <form method="POST" action="http://login.spotlite.com.au/password" accept-charset="UTF-8" id="frm-password"
              onsubmit="submitForgotPassword(); return false;"><input name="_token" type="hidden"
                                                                      value="3i7c4iYsz2Huf5ayxH60fmJl1iLS6F6otURhOKYM">

            <div class="form-group has-feedback">
                <input class="form-control" placeholder="Email" autocomplete="off" name="email" type="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row m-b-20">
                <div class="col-sm-12">
                    <div class="g-recaptcha" data-sitekey="6Ldtpg0UAAAAAEoU05qyVM0Mt6oJUYGLPewBwGDa">
                        <div style="width: 304px; height: 78px;">
                            <div>
                                <iframe src="https://www.google.com/recaptcha/api2/anchor?k=6Ldtpg0UAAAAAEoU05qyVM0Mt6oJUYGLPewBwGDa&amp;co=aHR0cDovL2xvZ2luLnNwb3RsaXRlLmNvbS5hdTo4MA..&amp;hl=en&amp;v=r20170126104253&amp;size=normal&amp;cb=59pyus3z920"
                                        title="recaptcha widget" width="304" height="78" frameborder="0" scrolling="no"
                                        name="undefined"></iframe>
                            </div>
                            <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response"
                                      style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;  display: none; "></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-sm-push-6 text-right">
                    <input class="btn btn-default btn-block btn-flat" href="#" type="submit" value="RESET">
                </div>
                <div class="col-sm-6 col-sm-pull-6">
                    <div style="padding-top: 5px; padding-bottom: 5px;">
                        <a href="http://login.spotlite.com.au/login">Back to login page</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Forgot component mounted.')
        }
    }
</script>