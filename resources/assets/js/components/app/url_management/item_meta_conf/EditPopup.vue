<template>
    <div class="modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <h4 class="modal-card-title">
                    Edit Meta Configuration
                </h4>
                <a @click.prevent="hideModal" class="close">&times;</a>
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal">
                            <single-edit-item-meta-conf v-for="(conf, key) in editingConfs" :conf="conf" @remove-conf="removeConf(key)" @updating-meta-conf="setConf(key, $event)">
                            </single-edit-item-meta-conf>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <p class="form-control-static">
                                        <a href="#" @click.prevent="addNewConf">
                                            ADD NEW CONFIGURATION <i class="glyphicon glyphicon-plus text-success"></i>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <a class="btn btn-primary btn-flat" href="#" @click.prevent="updateMetaConfs">UPDATE</a>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import singleEditItemMetaConf from './SingleEditItemMetaConf.vue';

    export default{
        components: {
            singleEditItemMetaConf
        },
        props: [
            'isActive',
            'itemMeta'
        ],
        data(){
            return {
                editingConfs: []
            }
        },
        mounted(){
            console.info('EditPopup component mounted.');
            this.loadConfs();
        },
        methods: {
            loadConfs(){
                axios.get(this.itemMeta.urls.conf_index).then(response => {
                    if (response.data.status == true) {
                        this.editingConfs = response.data.itemMetaConfs;
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            updateMetaConfs(){
                axios.post(this.itemMeta.urls.conf_store, this.updateItemMetaConfsData).then(response => {
                    if (response.data.status == true) {
                        this.$emit('updated-item-meta-confs');
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            setConf(key, conf){
                this.editingConfs[key] = conf;
            },
            addNewConf(){
                this.editingConfs.push({
                    element: null,
                    value: null,
                });
            },
            removeConf(key){
                console.info('key', key);
                console.info('this.editingConfs', this.editingConfs);
                this.editingConfs.splice(key, 1);
            },
            hideModal(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            updateItemMetaConfsData(){
                return {
                    item_meta_id: this.itemMeta.id,
                    confs: this.editingConfs
                }
            }
        }
    }
</script>

<style>

</style>