<template>
    <div class="modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Edit Item</p>
                <button class="close" @click.prevent="cancelDelete"><span aria-hidden="true">×</span></button>
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
                                <label for="txt-item-name" class="control-label col-sm-3">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txt-item-name" v-model="name">
                                </div>
                            </div>
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="is_active"> Is active?
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-flat" @click.prevent="updateItem" :disabled="isEditingItem">CONFIRM</button>
                        <button class="btn btn-default btn-flat" @click.prevent="cancelUpdate">CANCEL</button>
                    </div>
                </div>
            </footer>
        </div>
        <loading v-if="isEditingItem"></loading>
    </div>
</template>

<script>
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading
        },
        props: [
            'is-active',
            'item'
        ],
        data(){
            return {
                name: '',
                is_active: true,
                isEditingItem: false,
                errors: [],
            }
        },
        mounted(){
            console.info("EditPopup component mounted.");
            this.initSetItem();
        },
        methods: {
            initSetItem(){
                this.name = this.item.name;
                this.is_active = this.item.is_active != "n";
            },
            updateItem(){
                this.isEditingItem = true;
                axios.put(this.item.urls.update, this.updateItemData).then(response => {
                    this.isEditingItem = false;
                    if (response.data.status == true) {
                        this.confirmUpdate();
                    }
                }).catch(error => {
                    this.isEditingItem = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            confirmUpdate(){
                this.$emit('updated-item');
            },
            cancelUpdate(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            updateItemData(){
                return {
                    name: this.name,
                    is_active: this.is_active ? 'y' : 'n',
                }
            }
        }
    }
</script>

<style>

</style>