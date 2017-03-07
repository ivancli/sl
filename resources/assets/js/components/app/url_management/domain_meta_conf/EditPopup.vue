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
                            <single-edit-domain-meta-conf v-for="(conf, key) in editingConfs" :conf="conf" @remove-conf="removeConf(key)" @updating-meta-conf="setConf(key, $event)">
                            </single-edit-domain-meta-conf>
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
                    <a class="btn btn-primary btn-flat" href="#" @click.prevent="setMetaConfs">SET</a>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import singleEditDomainMetaConf from './SingleEditDomainMetaConf.vue';

    export default{
        components: {
            singleEditDomainMetaConf
        },
        props: [
            'isActive',
            'confs'
        ],
        data(){
            return {
                editingConfs: []
            }
        },
        mounted(){
            console.info('EditPopup component mounted.');
            this.initSetEditingConfs();
        },
        methods: {
            setMetaConfs(){
                this.$emit('set-confs', this.editingConfs);
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
            initSetEditingConfs(){
                this.editingConfs = this.confs;
            },
            hideModal(){
                this.$emit('hide-modal');
            }
        }
    }
</script>

<style>

</style>