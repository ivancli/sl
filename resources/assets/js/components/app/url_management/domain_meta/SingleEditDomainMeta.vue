<template>
    <div class="form-group required">
        <label class="col-sm-2 control-label">
            Meta
        </label>
        <div class="col-sm-4">
            <input type="text" class="form-control" v-model="meta.name">
        </div>
        <div class="col-sm-4">
            <select class="form-control" v-model="meta.type">
                <option value="">meta type</option>
                <option value="STATIC">Static Text</option>
                <option value="SELECT">Drop Down</option>
                <option value="BUTTON">Multiple Buttons</option>
            </select>
        </div>
        <div class="col-sm-2">
            <p class="form-control-static">
                <a href="#" class="text-muted" @click.prevent="updateMetaConf">

                </a>
                &nbsp;
                <a href="#" class="text-danger" @click.prevent="removeMeta">
                    <i class="glyphicon glyphicon-minus"></i>
                </a>
            </p>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'meta'
        ],
        mounted(){
            console.info("SingleEditDomainMeta is mounted.");
        },
        methods: {
            removeMeta(){
                this.$emit('remove-meta');
            }
        }
    }
</script>

<style>

</style>