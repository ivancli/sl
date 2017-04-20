<template>
    <div class="form-group required">
        <label class="col-md-2 control-label">
            Meta
        </label>
        <div class="col-md-2">
            <input type="text" class="form-control" v-model="editingMeta.element">
        </div>
        <div class="col-md-3">
            <select class="form-control" v-model="editingMeta.format_type">
                <option value="">Format Type (Default: Text)</option>
                <option value="decimal">Decimal</option>
                <option value="boolean">Boolean</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" v-model="editingMeta.historical_type">
                <option value="">Historical Type</option>
                <option value="price">Price</option>
            </select>
        </div>
        <div class="col-md-2">
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
                    element: null,
                    format_type: null,
                    historical_type: null,
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