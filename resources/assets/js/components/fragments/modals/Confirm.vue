<template>
    <div class="modal confirm-modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <h4 class="modal-card-title">Oops! Something went wrong.</h4>
                <a @click.prevent="hideModal" class="close">&times;</a>
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12" v-text="content">

                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <a class="btn btn-primary btn-flat" href="#" @click.prevent="confirm">CONFIRM</a>
                <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">OK</a>
            </footer>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'content',
        ],
        mounted(){
            console.info('Confirm component mounted.');
        },
        methods: {
            confirm: function () {
//                this.$emit()
            },
            hideModal: function () {
                this.$emit('hideErrorModal');
            }
        },
        computed: {
            isActive(){
                if (typeof this.content == 'string') {
                    return this.content.length > 0
                }
                return false;
            },
        }
    }
</script>

<style>

</style>