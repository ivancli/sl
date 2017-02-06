<template>
    <div>
        <div class="login-box m-t-0 m-b-50">
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                    <li v-for="error in errors">
                        <div v-if="error.constructor != Array" v-text="error"></div>
                        <div v-else v-for="message in error" v-text="message"></div>
                    </li>
                </ul>
                <form method="POST" action="/login" id="frm-login" @submit.prevent="submitLogin">
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Email" autocomplete="off" name="email" type="email"
                               :disabled="isLoggingIn" v-model="email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Password" name="password" type="password"
                               :disabled="isLoggingIn" v-model="password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="remember" id="remember"
                                           :disabled="isLoggingIn"> &nbsp; Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button type="submit" class="btn btn-primary btn-flat" :disabled="isLoggingIn"
                                    v-text="isLoggingIn ? 'LOGGING IN' : 'SIGN IN'"></button>
                        </div>
                    </div>
                </form>
                <div style="margin-bottom: 10px;"></div>
                <a href="/forgot">I forgot my password</a><br>
                <a href="/register" class="text-center">New to SpotLite? Sign up now!</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: ()=> {
            return {
                isLoggingIn: false,
                email: '',
                password: '',
                errors: {}
            }
        },
        computed: {
            loginData: function () {
                return {
                    email: this.email,
                    password: this.password,
                }
            }
        },
        methods: {
            submitLogin: function () {
                this.isLoggingIn = true;
                this.errors = {};
                axios.post('/login', this.loginData).then(response=> {
                    this.isLoggingIn = false;
                    console.info('response', response)
                }).catch(error=> {
                    this.isLoggingIn = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        console.info(error.response)
                        this.errors = error.response.data;
                    }
                })
            }
        },
        mounted() {
            console.log('Login component mounted.')
        }
    }
</script>