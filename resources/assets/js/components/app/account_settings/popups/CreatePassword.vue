<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 text-center m-b-10">
                                <img src="/images/logo-fixed-2.png" width="160">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="jumbotron set-password-container">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8 text-center">
                                            <p>Please set your password.</p>

                                            <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                                                <li v-for="error in errors">
                                                    <div v-if="error.constructor != Array" v-text="error"></div>
                                                    <div v-else v-for="message in error" v-text="message"></div>
                                                </li>
                                            </ul>
                                            <form class="form-horizontal">
                                                <div class="form-group">
                                                    <input type="password" class="form-control" placeholder="Password" v-model="password">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class="form-control" placeholder="Confirm password" v-model="password_confirmation">
                                                </div>
                                                <div class="form-group text-center">
                                                    <button class="btn btn-primary btn-flat" @click.prevent="onClickSetPassword" :disabled="isSettingPassword">SET PASSWORD</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <loading v-if="isSettingPassword"></loading>
    </div>
</template>

<script>
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        components:{
            loading
        },
        data(){
            return {
                password: '',
                password_confirmation: '',
                isSettingPassword: false,
                errors: {},
            }
        },
        mounted(){
            console.info("CreatePassword component mounted.");
        },
        methods: {
            onClickSetPassword(){
                this.isSettingPassword = true;
                axios.put(user.profileUrls.password, this.setPasswordRequestData).then(response => {
                    this.isSettingPassword = false;
                    if (response.data.status === true) {
                        this.emitSetPassword();
                    }
                }).catch(error => {
                    this.isSettingPassword = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            emitSetPassword(){
                this.$emit('set-password');
            },
        },
        computed: {
            setPasswordRequestData(){
                return {
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                };
            }
        }
    }
</script>

<style>
    .set-password-container p {
        font-size: 15px;
    }
</style>