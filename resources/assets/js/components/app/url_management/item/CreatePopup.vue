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
                        <form action="" class="form-horizontal">
                            <div class="form-group">
                                <label for="txt-item-name" class="control-label col-sm-4">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="txt-item-name" v-model="name">
                                </div>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="is_active"> Is active?
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-flat" @click.prevent="confirmCreate">CONFIRM</button>
                        <button class="btn btn-default btn-flat" @click.prevent="cancelCreate">CANCEL</button>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'is-active',
            'url',
        ],
        data(){
            return {
                name: '',
                is_active: true
            }
        },
        mounted(){
            console.info("Create component mounted.");
        },
        methods: {
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
                    is_active: this.is_active,
                }
            }
        }
    }
</script>

<style>

</style>