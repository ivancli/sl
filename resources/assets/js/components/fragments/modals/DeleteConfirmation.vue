<template>
    <div class="modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <section class="modal-card-body">
                <button class="close" @click.prevent="cancelDelete"><span aria-hidden="true">×</span></button>
                <div class="delete-popup-content-container">
                    <h3 class="text-center delete-popup-heading m-b-20">
                        Are you sure you want to delete this {{ modalTitle | capitalise }}?
                    </h3>
                    <p class="delete-popup-subheading m-b-20">
                        By deleting this {{ modalTitle }}, you will lose the following:
                    </p>
                    <div class="row delete-popup-list m-b-20">
                        <div class="col-sm-12">
                            <ul class="warning-list">
                                <li v-for="msg in modalWarningList" v-text="msg"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input class="chk-confirm-delete" type="checkbox" v-model="isConfirmed">&nbsp;&nbsp;I have read and understood that this action cannot be undone.
                        </label>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-danger btn-flat" @click.prevent="confirmDelete" :disabled="!isConfirmed">DELETE</button>
                        <button class="btn btn-default btn-flat" @click.prevent="cancelDelete">CANCEL</button>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import capitalise from '../../../filters/capitalise';

    export default {
        props: [
            'delete-params'
        ],
        data(){
            return {
                isConfirmed: false,
            };
        },
        mounted() {
            console.log('DeleteConfirmation modal component mounted.')
        },
        computed: {
            modalTitle: function () {
                return this.deleteParams.title;
            },
            modalWarningList: function () {
                return this.deleteParams.list;
            },
            isActive: function () {
                return this.deleteParams.active;
            },
        },
        methods: {
            confirmDelete(){
                this.$emit('confirmDelete');
            },
            cancelDelete(){
                this.$emit('cancelDelete');
            }
        }
    }
</script>

<style>
    .delete-popup-content-container {
        padding-left: 50px;
        padding-right: 50px;
    }

    .warning-list {
        padding-left: 0px;
    }

    .warning-list li {
        list-style: none;
    }

    .warning-list li:before {
        content: "\F00D";
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        color: #a94442;
        padding-right: 10px;
    }
</style>