<template>
    <div class="form-group required">
        <label class="col-sm-2 control-label">
            Meta
        </label>
        <div class="col-sm-4">
            <input type="text" class="form-control" v-model="editingMeta.name">
        </div>
        <div class="col-sm-4">
            <select class="form-control" v-model="editingMeta.type">
                <option value="">meta type</option>
                <option value="STATIC">Static Text</option>
                <option value="SELECT">Drop Down</option>
                <option value="BUTTON">Multiple Buttons</option>
            </select>
        </div>
        <div class="col-sm-2">
            <p class="form-control-static">
                <a href="#" class="text-muted" @click.prevent="updateMetaConf">
                    <i class="glyphicon glyphicon-cog"></i>
                </a>
                &nbsp;
                <a href="#" class="text-danger" @click.prevent="removeMeta">
                    <i class="glyphicon glyphicon-minus"></i>
                </a>
            </p>
        </div>
        <edit-popup :is-active="isMetaConfActive" :confs="meta.confs" @hide-modal="hideEditPopup" @set-confs="setMetaConfs"></edit-popup>
    </div>
</template>

<script>
    import editPopup from '../domain_meta_conf/EditPopup.vue';

    export default{
        components: {
            editPopup
        },
        props: [
            'meta'
        ],
        data(){
            return {
                isMetaConfActive: false,
                editingMeta: {
                    name: null,
                    type: null,
                    confs: []
                },
            };
        },
        mounted(){
            console.info("SingleEditDomainMeta is mounted.");
            this.initSetEditingMeta();
        },
        watch: {
            editingMeta: function () {
                this.updatingDomainMeta();
            }
        },
        methods: {
            initSetEditingMeta(){
                this.editingMeta = this.meta;
            },
            removeMeta(){
                this.$emit('remove-meta');
            },
            updateMetaConf(){
                this.isMetaConfActive = true;
            },
            hideEditPopup(){
                this.isMetaConfActive = false;
            },
            setMetaConfs(confs){
                this.editingMeta.confs = confs;
                this.hideEditPopup();
            },
            updatingDomainMeta(){
                this.$emit('updating-domain-meta', this.editingMeta);
            }
        }
    }
</script>

<style>

</style>