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
                        <label for="txt-full-path" class="col-sm-2 control-label">Full path</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="txt-full-path" v-model="fullPath">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="editUrl">UPDATE</button>
                            <a href="/url-management/url" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isEditingUrl || isLoadingUrl"></loading>
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
                fullPath: '',
                isEditingUrl: false,
                isLoadingUrl: false,
                errors: {},
            }
        },
        mounted() {
            console.info('Edit component is mounted.');
            this.loadUrl();
        },
        methods: {
            loadUrl(){
                this.isLoadingUrl = true;
                axios.get(editingUrl.urls.show).then(response => {
                    this.isLoadingUrl = false;
                    if (response.data.status == true) {
                        let url = response.data.url;
                        this.name = url.name;
                        this.fullPath = url.full_path;
                    }
                }).catch(error => {
                    this.isLoadingUrl = false;
                })
            },
            editUrl(){
                this.isEditingUrl = true;
                axios.put(editingUrl.urls.update, this.editUrlData).then(response => {
                    this.isEditingUrl = false;
                    if (response.data.status == true) {
                        window.location.href = '/url-management/url';
                    }
                }).catch(error => {
                    this.isEditingUrl = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            }
        },
        computed: {
            editUrlData: function () {
                return {
                    full_path: this.fullPath,
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
