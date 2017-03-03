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
                        <label class="col-sm-2 control-label">Roles</label>
                        <div class="col-sm-10">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="vertical-align-middle text-center">
                                        <div>
                                            Available Roles
                                        </div>
                                        <div>
                                            <select multiple class="form-control sl-form-control"
                                                    v-model="inputRoleIds">
                                                <option v-for="role in roles"
                                                        v-if="selectedRoleIds.indexOf(role.id) == -1"
                                                        v-text="role.display_name"
                                                        :value="role.id"></option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="vertical-align-middle text-center shrink">
                                        <div>
                                            <a href="#" @click.prevent="grantRole">
                                                <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <a href="#" @click.prevent="revokeRole">
                                                <i class="fa fa-arrow-left"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="vertical-align-middle text-center">
                                        <div class="text-center">
                                            Granted Roles
                                        </div>
                                        <div>
                                            <select multiple class="form-control sl-form-control"
                                                    v-model="outputRoleIds">
                                                <option v-for="role in selectedRoles"
                                                        v-text="role.display_name"
                                                        :value="role.id"></option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="createUser">CREATE</button>
                            <a href="/user-management/user" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isCreatingUser || isLoadingRoles"></loading>
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
                roles: [],
                selectedRoles: [],
                inputRoleIds: [],
                outputRoleIds: [],
                isCreatingUser: false,
                isLoadingRoles: false,
                errors: {},
            }
        },
        mounted() {
            console.info('Create component is mounted.');
            this.loadRoles();
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
            },
            loadRoles(){
                this.isLoadingRole = true;
                axios.get('/user-management/role').then(response => {
                    this.isLoadingRole = false;
                    if (response.data.status == true) {
                        this.roles = response.data.roles;
                    }
                }).catch(error => {
                    this.isLoadingRole = false;
                })
            },
            grantRole(){
                var roles = this.roles.filter((role) => {
                    return this.inputRoleIds.indexOf(role.id) > -1;
                });
                this.selectedRoles = this.selectedRoles.concat(roles);
            },
            revokeRole(){
                this.selectedRoles = this.selectedRoles.filter((role)=> {
                    return this.outputRoleIds.indexOf(role.id) == -1;
                });
            }
        },
        computed: {
            createUserData: function () {
                var data = {
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                    role_ids: this.selectedRoleIds,
                };
                if (this.password != '') {
                    data.password = this.password;
                    data.password_confirmation = this.passwordConfirmation;
                }
                return data;
            },
            selectedRoleIds: function () {
                var ids = [];
                for (var selectedRoleKey in this.selectedRoles) {
                    if (this.selectedRoles.hasOwnProperty(selectedRoleKey)) {
                        ids.push(this.selectedRoles[selectedRoleKey].id);
                    }
                }
                return ids;
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

    select[multiple] {
        min-width: 200px;
    }

    @media (max-width: 991px) {
        select[multiple] {
            min-width: 100px;
            max-width: 150px;
        }
    }
</style>
