<template>
    <div class="modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Create New Item</p>
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
                        <button class="btn btn-primary btn-flat" @click.prevent="createItem" :disabled="isCreatingItem">CONFIRM</button>
                        <button class="btn btn-default btn-flat" @click.prevent="cancelCreate">CANCEL</button>
                    </div>
                </div>
            </footer>
        </div>
        <loading v-if="isCreatingItem"></loading>
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
            'url',
        ],
        data(){
            return {
                name: '',
                is_active: true,
                isCreatingItem: false,
                errors: [],
            }
        },
        mounted(){
            console.info("CreatePopup component mounted.");
        },
        methods: {
            createItem(){
                this.isCreatingItem = true;
                axios.post(this.url.urls.item_store, this.createItemData).then(response => {
                    this.isCreatingItem = false;
                    if (response.data.status == true) {
                        this.confirmCreate();
                    }
                }).catch(error => {
                    this.isCreatingItem = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            confirmCreate(){
                this.$emit('created-new-item');
            },
            cancelCreate(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            createItemData(){
                return {
                    url_id: this.url.id,
                    name: this.name,
                    is_active: this.is_active ? 'y' : 'n',
                }
            }
        }
    }
</script>

<style>

</style>