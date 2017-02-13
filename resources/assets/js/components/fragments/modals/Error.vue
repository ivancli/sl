<template>
    <div class="modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <h4 class="modal-card-title">Oops! Something went wrong.</h4>
                <a @click.prevent="hideModal" class="close">&times;</a>
            </header>
            <section class="modal-card-body">
                <!-- Content ... -->
                <div class="row">
                    <div class="col-sm-12" v-text="errorMsg">

                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <a class="btn btn-default btn-flat" @click.prevent="hideModal">OK</a>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'modalErrors'
        ],
        data: ()=> {
            return {};
        },
        methods: {
            hideModal: function () {
                this.$emit('hideErrorModal');
            }
        },
        mounted() {
            console.log('modal component mounted.')
        },
        computed: {
            isActive: function () {
                if (typeof this.modalErrors == 'object') {
                    var props = Object.getOwnPropertyNames(this.modalErrors);
                    props.splice(props.indexOf('__ob__'), 1);
                    return props.length > 0
                } else if (typeof this.modalErrors == 'array' || typeof this.modalErrors == 'string') {
                    return this.modalErrors.length > 0;
                }
                return false;
            },
            errorMsg: function () {
                var errorMsg = "";
                if (typeof this.modalErrors == 'object' || typeof this.modalErrors == 'array') {
                    for (var k in this.modalErrors) {
                        if (this.modalErrors.hasOwnProperty(k) || this.modalErrors[k]) {
                            if (typeof this.modalErrors[k] == 'array' || typeof this.modalErrors[k] == 'object') {
                                for (var k2 in this.modalErrors[k]) {
                                    if (this.modalErrors[k].hasOwnProperty(k2) || this.modalErrors[k][k2]) {
                                        errorMsg += this.modalErrors[k][k2] + ' ';
                                    }
                                }
                            } else {
                                errorMsg += this.modalErrors[k] + ' ';
                            }
                        }
                    }
                } else if (typeof this.modalErrors == 'string') {
                    errorMsg = this.modalErrors;
                }
                return errorMsg;
            }
        }
    }
</script>

<style>
    @media screen and (min-width: 301px) {
        .modal-content, .modal-card {
            width: 300px;
        }
    }
</style>