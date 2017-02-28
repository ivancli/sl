<template>
    <div class="modal confirm-modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <h4 class="modal-card-title" v-text="title"></h4>
                <a @click.prevent="hideModal" class="close">&times;</a>
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12" v-html="content">

                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <a class="btn btn-primary btn-flat" href="#" @click.prevent="confirm">CONFIRM</a>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'title',
            'content',
        ],
        mounted(){
            console.info('Confirm component mounted.');
        },
        methods: {
            confirm: function () {
                this.$emit('confirm')
            },
            hideModal: function () {
                this.$emit('hide');
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
    .confirm-modal {
        -ms-word-wrap: normal;
        word-wrap: normal;
        white-space: normal;
    }

    @media screen and (min-width: 301px) {
        .confirm-modal .modal-content,
        .confirm-modal .modal-card {
            width: 300px;
        }
    }
</style>