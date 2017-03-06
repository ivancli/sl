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

                    <single-edit-domain-meta v-for="(meta, key) in domain.metas" :meta="meta"
                                             @remove-meta="removeMeta(key)"
                                             @updating-domain-meta="setMeta(key, $event)"></single-edit-domain-meta>
                    <!--TODO use store to loop meta controllers-->

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <p class="form-control-static">
                                <a href="#" class="text-muted" @click.prevent="addNewMeta">
                                    ADD NEW META <i class="glyphicon glyphicon-plus text-success"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="editDomainMeta">UPDATE
                            </button>
                            <a href="/url-management/domain" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isEditingDomainMeta || isLoadingDomain"></loading>
    </section>
</template>

<script>
    import loading from '../../../Loading.vue';

    import singleEditDomainMeta from './SingleEditDomainMeta.vue';

    export default{
        components: {
            loading,
            singleEditDomainMeta
        },
        data(){
            return {
                domain: null,
                isEditingDomainMeta: false,
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
            editDomainMeta(){
                axios.put(editingDomain.modelUrls.meta_update, this.editDomainMetaData).then(response => {
                    this.isEditingDomainMeta = false;
                    if (response.data.status == true) {
                        window.location.href = '/url-management/domain';
                    }
                }).catch(error => {
                    this.isEditingDomainMeta = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                });
            },
            addNewMeta(){
                this.domain.metas.push({
                    name: '',
                    type: ''
                });
            },
            removeMeta(key){
                this.domain.metas.splice(key, 1);
            },
            setMeta(key, meta){
                this.domain.metas[key] = meta;
            }
        },
        computed: {
            editDomainMetaData(){
                return {
                    metas: this.domain.metas
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
