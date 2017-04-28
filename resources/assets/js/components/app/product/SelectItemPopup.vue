<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>It appears that there are multiple options available in provided Product Page.</p>
                        <p>Please select from the following options:</p>
                        <form class="form-horizontal">
                            form here pls
                        </form>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <a class="btn btn-primary btn-flat" href="#" @click.prevent="updateSite">SAVE</a>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
        <loading v-if="isUpdatingSite"></loading>
    </div>
</template>

<script>
    import loading from '../../Loading.vue';

    export default{
        props: [
            'editingSite'
        ],
        components: {
            loading
        },
        data(){
            return {
                isUpdatingSite: false
            }
        },
        mounted(){
            console.info('SelectItemPopup is mounted.');
        },
        methods: {
            updateSite(){

            },
            hideModal(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            site(){
                return this.editingSite;
            }
        }
    }
</script>

<style>

</style>