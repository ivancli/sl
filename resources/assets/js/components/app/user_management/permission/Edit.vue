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
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="editPermission">UPDATE
                            </button>
                            <a href="/user-management/permission" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isEditingPermission || isLoadingPermission"></loading>
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
                name: '',
                displayName: '',
                description: '',
                isEditingPermission: false,
                isLoadingPermission: false,
                errors: {},
            }
        },
        mounted() {
            console.info('Edit component is mounted.');
            this.loadPermission();
        },
        methods: {
            loadPermission(){
                this.isLoadingPermission = true;
                axios.get(editingPermission.urls.show).then(response => {
                    this.isLoadingPermission = false;
                    if (response.data.status == true) {
                        var permission = response.data.permission;
                        this.name = permission.name;
                        this.displayName = permission.display_name;
                        this.description = permission.description;
                    }
                }).catch(error => {
                    this.isLoadingPermission = false;
                })
            },
            editPermission(){
                this.isEditingPermission = true;
                axios.put(editingPermission.urls.update, this.editPermissionData).then(response => {
                    this.isEditingPermission = false;
                    if (response.data.status == true) {
                        window.location.href = '/user-management/permission';
                    }
                }).catch(error => {
                    this.isEditingPermission = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            editPermissionData: function () {
                return {
                    name: this.name,
                    display_name: this.displayName,
                    description: this.description,
                };
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
