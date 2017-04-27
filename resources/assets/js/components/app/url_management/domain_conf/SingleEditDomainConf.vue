<template>
    <div class="form-group">
        <div class="col-sm-5">
            <input type="text" class="form-control" placeholder="Element" autocomplete="off" v-model="editingConf.element">
        </div>
        <div class="col-sm-5">
            <input type="text" class="form-control" placeholder="Value" autocomplete="off" v-model="editingConf.value">
        </div>
        <div class="col-sm-2">
            <p class="form-control-static">
                <a href="#" class="text-danger" @click.prevent="removeConf">
                    <i class="glyphicon glyphicon-minus"></i>
                </a>
            </p>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'conf'
        ],
        mounted(){
            console.info('SingleEditDomainConf component mounted.');
            this.initSetEditingConf();
        },
        data(){
            return {
                editingConf: {
                    element: null,
                    value: null
                }
            };
        },
        watch: {
            editingConf(){
                this.updatingDomainConf();
            },
            /*
             * TODO look into this when we have time
             * it's weird that conf is not correctly watched in parent component
             * */
            conf(){
                this.editingConf = this.conf;
            }
        },
        methods: {
            initSetEditingConf(){
                this.editingConf = this.conf;
            },
            updatingDomainConf(){
                this.$emit('updating-domain-conf', this.editingConf);
            },
            removeConf(){
                this.$emit('remove-conf')
            }
        }
    }
</script>

<style>

</style>