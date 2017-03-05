<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid" v-if="domain != null">
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

                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Domain</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">
                                <a :href="domain.full_path" target="_blank" v-text="domain.full_path"></a>
                            </p>
                        </div>
                    </div>

                    <!--TODO use store to loop meta controllers-->

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <p class="form-control-static">
                                <a href="#" class="text-muted">
                                    ADD NEW META <i class="glyphicon glyphicon-plus text-success"></i>
                                </a>
                            </p>
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
    import loading from '../../../Loading.vue';

    export default{
        components: {
            loading
        },
        data(){
            return {
                domain: null,
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
                        this.domain = response.data.domain;
                    }
                }).catch(error => {
                    this.isLoadingDomain = false;
                })
            },
        },
        computed: {}
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
