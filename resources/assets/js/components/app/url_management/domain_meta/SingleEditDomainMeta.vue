<template>
    <div class="form-group required">
        <label class="col-sm-2 control-label">
            Meta {{ index }}
        </label>
        <div class="col-sm-5">
            <select>
                <option value="">meta type</option>
            </select>
        </div>
        <div class="col-sm-5">
            <input type="text" class="form-control">
        </div>
    </div>
</template>

<script>
    export default{
        props:[
            'index'
        ],
        mounted(){
            console.info("SingleEditDomainMeta is mounted.");
        }
    }
</script>

<style>

</style>