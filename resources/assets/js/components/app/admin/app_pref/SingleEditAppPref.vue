<template>
    <div class="form-group">
        <label :for="editingElement" v-text="editingElement" class="control-label col-sm-4"></label>
        <div class="col-sm-8">
            <input type="text" v-model="editingValue" :id="editingElement" class="form-control">
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'element',
            'value'
        ],
        data(){
            return {
                editingElement: null,
                editingValue: null,
            };
        },
        mounted(){
            console.info('SinglEditAppPref component is mounted.');
            this.initSetPref();
        },
        watch: {
            editingElement(){
                this.updatingPref()
            },
            editingValue(){
                this.updatingPref()
            }
        },
        methods: {
            initSetPref(){
                this.editingElement = this.element;
                this.editingValue = this.value;
            },
            updatingPref(){
                this.$emit('updating-pref', this.pref);
            }
        },
        computed: {
            pref(){
                return {
                    element: this.editingElement,
                    value: this.editingValue
                }
            }
        }

    }
</script>

<style>

</style>