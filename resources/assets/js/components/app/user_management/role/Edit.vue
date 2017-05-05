<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row">
                    <div class="col-sm-offset-2">
                        <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                            <li v-for="error in errors">
                                <div v-if="error.constructor != Array" v-text="error"></div>
                                <div v-else v-for="message in error" v-text="message"></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <form class="form-horizontal" onsubmit="return false;">
                    <div class="form-group required">
                        <label for="txt-name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="txt-name" v-model="name">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="txt-display-name" class="col-sm-2 control-label">Display name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="txt-display-name" v-model="displayName">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txt-description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="txt-description" v-model="description"
                                      rows="7"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Permissions</label>
                        <div class="col-sm-10">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="vertical-align-middle text-center">
                                        <div>
                                            Available Permissions
                                        </div>
                                        <div>
                                            <select multiple class="form-control sl-form-control"
                                                    v-model="inputPermissionIds">
                                                <option v-for="permission in permissions"
                                                        v-if="selectedPermissionIds.indexOf(permission.id) == -1"
                                                        v-text="permission.display_name"
                                                        :value="permission.id"></option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="vertical-align-middle text-center shrink">
                                        <div>
                                            <a href="#" @click.prevent="grantPermission">
                                                <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <a href="#" @click.prevent="revokePermission">
                                                <i class="fa fa-arrow-left"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="vertical-align-middle text-center">
                                        <div class="text-center">
                                            Granted Permissions
                                        </div>
                                        <div>
                                            <select multiple class="form-control sl-form-control"
                                                    v-model="outputPermissionIds">
                                                <option v-for="permission in selectedPermissions"
                                                        v-text="permission.display_name"
                                                        :value="permission.id"></option>
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
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="editRole">UPDATE</button>
                            <a href="/user-management/role" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isEditingRole || isLoadingRole || isLoadingPermissions"></loading>
    </section>
</template>

<script>
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading
        },
        data(){
            return {
                name: '',
                displayName: '',
                description: '',
                permissions: [],
                selectedPermissions: [],
                inputPermissionIds: [],
                outputPermissionIds: [],
                isEditingRole: false,
                isLoadingRole: false,
                isLoadingPermissions: false,
                errors: {},
            }
        },
        mounted() {
            console.info('Edit component is mounted.');
            this.loadRole();
            this.loadPermissions();
        },
        methods: {
            loadRole(){
                this.isLoadingRole = true;
                axios.get(editingRole.urls.show).then(response => {
                    this.isLoadingRole = false;
                    if (response.data.status == true) {
                        var role = response.data.role;
                        this.name = role.name;
                        this.displayName = role.display_name;
                        this.description = role.description;
                        this.selectedPermissions = role.selectedPermissions;
                    }
                }).catch(error => {
                    this.isLoadingRole = false;
                })
            },
            loadPermissions(){
                this.isLoadingPermission = true;
                axios.get('/user-management/permission').then(response => {
                    this.isLoadingPermission = false;
                    if (response.data.status == true) {
                        this.permissions = response.data.permissions;
                    }
                }).catch(error => {
                    this.isLoadingPermission = false;
                })
            },
            editRole(){
                this.isEditingRole = true;
                axios.put(editingRole.urls.update, this.editRoleData).then(response => {
                    this.isEditingRole = false;
                    if (response.data.status == true) {
                        window.location.href = '/user-management/role';
                    }
                }).catch(error => {
                    this.isEditingRole = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            grantPermission(){
                var permissions = this.permissions.filter((permission) => {
                    return this.inputPermissionIds.indexOf(permission.id) > -1;
                });
                this.selectedPermissions = this.selectedPermissions.concat(permissions);
            },
            revokePermission(){
                this.selectedPermissions = this.selectedPermissions.filter((permission) => {
                    return this.outputPermissionIds.indexOf(permission.id) == -1;
                });
            }
        },
        computed: {
            editRoleData: function () {
                return {
                    name: this.name,
                    display_name: this.displayName,
                    description: this.description,
                    permission_ids: this.selectedPermissionIds
                };
            },
            selectedPermissionIds: function () {
                var ids = [];
                for (var selectedPermissionKey in this.selectedPermissions) {
                    if (this.selectedPermissions.hasOwnProperty(selectedPermissionKey)) {
                        ids.push(this.selectedPermissions[selectedPermissionKey].id);
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
