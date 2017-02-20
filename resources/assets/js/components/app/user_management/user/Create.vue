<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="col-sm-offset-2">
                    <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                        <li v-for="error in errors">
                            <div v-if="error.constructor != Array" v-text="error"></div>
                            <div v-else v-for="message in error" v-text="message"></div>
                        </li>
                    </ul>
                </div>
                <form class="form-horizontal" onsubmit="return false;">
                    <div class="form-group required">
                        <label for="txt-first-name" class="col-sm-2 control-label">First name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="txt-first-name" v-model="firstName">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="txt-last-name" class="col-sm-2 control-label">Last name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="txt-last-name" v-model="lastName">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="txt-email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="txt-email" v-model="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txt-password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="txt-password" v-model="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txt-confirm-password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="txt-confirm-password" v-model="passwordConfirmation">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-sm btn-flat" @click="createUser">CREATE</button>
                            <a href="/user-management/user" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isCreatingUser"></loading>
    </section>
</template>

<script>
    import loading from '../../../Loading.vue';

    export default{
        components: {
            loading
        },
        data(){
            return {
                firstName: '',
                lastName: '',
                email: '',
                password: '',
                passwordConfirmation: '',
                isCreatingUser: false,
                errors: {},
            }
        },
        mounted() {
            console.info('Create component is mounted.');
        },
        methods: {
            createUser(){
                this.isCreatingUser = true;
                axios.post('/user-management/user', this.createUserData).then(response=> {
                    this.isCreatingUser = false;
                    if (response.data.status == true) {
                        window.location.href = '/user-management/user';
                    }
                }).catch(error=> {
                    this.isCreatingUser = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            createUserData: function () {
                var data = {
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                };
                if (this.password != '') {
                    data.password = this.password;
                    data.password_confirmation = this.passwordConfirmation;
                }
                return data;
            }
        }
    }
</script>

<style>
    .required label:before {
        content: '*';
        color: #ff0000;
        padding-right: 5px;
        font-weight: bold;
    }
</style>
