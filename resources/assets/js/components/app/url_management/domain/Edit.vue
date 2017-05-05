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
                        <label for="txt-full-path" class="col-sm-2 control-label">Full path</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="txt-full-path" v-model="fullPath"
                                   disabled="disabled">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="editDomain">UPDATE</button>
                            <a href="/url-management/domain" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isEditingDomain || isLoadingDomain"></loading>
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
                fullPath: '',
                isEditingDomain: false,
                isLoadingDomain: false,
                errors: {},
            }
        },
        mounted() {
            console.info('Edit component is mounted.');
            this.loadDomain();
        },
        methods: {
            loadDomain(){
                this.isLoadingDomain = true;
                axios.get(editingDomain.modelUrls.show).then(response => {
                    this.isLoadingDomain = false;
                    if (response.data.status == true) {
                        let domain = response.data.domain;
                        this.name = domain.name;
                        this.fullPath = domain.full_path;
                    }
                }).catch(error => {
                    this.isLoadingDomain = false;
                })
            },
            editDomain(){
                this.isEditingDomain = true;
                axios.put(editingDomain.modelUrls.update, this.editDomainData).then(response => {
                    this.isEditingDomain = false;
                    if (response.data.status == true) {
                        window.location.href = '/url-management/domain';
                    }
                }).catch(error => {
                    this.isEditingDomain = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            editDomainData: function () {
                return {
                    name: this.name,
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
