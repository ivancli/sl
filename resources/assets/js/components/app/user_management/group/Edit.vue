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
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="editGroup">UPDATE</button>
                            <a href="/user-management/group" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isEditingGroup || isLoadingGroup"></loading>
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
                isEditingGroup: false,
                isLoadingGroup: false,
                errors: {},
            }
        },
        mounted() {
            console.info('Edit component is mounted.');
            this.loadGroup();
        },
        methods: {
            loadGroup(){
                this.isLoadingGroup = true;
                axios.get(editingGroup.urls.show).then(response=> {
                    this.isLoadingGroup = false;
                    if (response.data.status == true) {
                        var group = response.data.group;
                        this.name = group.name;
                        this.displayName = group.display_name;
                        this.description = group.description;
                    }
                }).catch(error=> {
                    this.isLoadingGroup = false;
                })
            },
            editGroup(){
                this.isEditingGroup = true;
                axios.put(editingGroup.urls.update, this.editGroupData).then(response=> {
                    this.isEditingGroup = false;
                    if (response.data.status == true) {
                        window.location.href = '/user-management/group';
                    }
                }).catch(error=> {
                    this.isEditingGroup = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            editGroupData: function () {
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
