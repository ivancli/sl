<template>
    <div class="modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Edit Meta</p>
                <button class="close" @click.prevent="cancelCreate"><span aria-hidden="true">×</span></button>
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-offset-3 col-sm-9">
                                <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                                    <li v-for="error in errors">
                                        <div v-if="error.constructor != Array" v-text="error"></div>
                                        <div v-else v-for="message in error" v-text="message"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <form class="form-horizontal" onsubmit="return false;">
                            <div class="form-group">
                                <label for="txt-meta-element" class="control-label col-sm-3">Element</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txt-meta-element" v-model="element">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txt-meta-value" class="control-label col-sm-3">Value</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txt-meta-value" v-model="value">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sel-historical-type" class="control-label col-sm-3">Historical Type</label>
                                <div class="col-sm-9">
                                    <select id="sel-historical-type" class="form-control" v-model="historical_type">
                                        <option value="">None</option>
                                        <option value="price">Price</option>
                                        <!--TODO more will be coming such as stock, colour and size etc-->
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-flat" @click.prevent="updateMeta" :disabled="isUpdatingMeta">CONFIRM</button>
                        <button class="btn btn-default btn-flat" @click.prevent="cancelUpdate">CANCEL</button>
                    </div>
                </div>
            </footer>
        </div>
        <loading v-if="isCreatingMeta"></loading>
    </div>
</template>

<script>
    import loading from '../../../Loading.vue';

    export default{
        components: {
            loading
        },
        props: [
            'is-active',
            'item-meta',
        ],
        data(){
            return {
                element: '',
                value: '',
                historical_type: '',
                isEditingMeta: false,
                errors: [],
            }
        },
        mounted(){
            console.info("EditPopup component mounted.");
            this.initSetMeta();
        },
        methods: {
            initSetMeta(){
                this.element = this.itemMeta.element;
                this.value = this.itemMeta.value;
                this.historical_type = this.itemMeta.historical_type;
            },
            updateMeta(){
                this.isEditingMeta = true;
                axios.put(this.itemMeta.urls.update, this.updateMetaData).then(response => {
                    this.isEditingMeta = false;
                    if (response.data.status == true) {
                        this.confirmUpdate();
                    }
                }).catch(error => {
                    this.isEditingMeta = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            confirmUpdate(){
                this.$emit('updated-item-meta');
            },
            cancelUpdate(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            updateMetaData(){
                let data = {
                    element: this.element,
                };
                if (this.value) {
                    data.value = this.value;
                }
                if(this.historical_type){
                    data.historical_type = this.historical_type;
                }
                return data;
            }
        }
    }
</script>

<style>

</style>