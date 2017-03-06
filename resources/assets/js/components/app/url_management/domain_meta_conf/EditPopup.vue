<template>
    <div class="modal" :class="isActive ? 'is-active' : ''">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <h4 class="modal-card-title">
                    Edit Meta Configuration
                </h4>
                <a @click.prevent="hideModal" class="close">&times;</a>
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Element" autocomplete="off">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="Value" autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <a class="btn btn-primary btn-flat" href="#" @click.prevent="setMetaConfs">SET</a>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'isActive',
            'confs'
        ],
        data(){
            return {
                editingConfs: []
            }
        },
        mounted(){
            console.info('EditPopup component mounted.');
            this.initSetEditingConfs();
        },
        methods: {
            setMetaConfs(){
                this.$emit('set-confs', this.editingConfs);
            },
            initSetEditingConfs(){
                this.editingConfs = this.confs;
            },
            hideModal(){
                this.$emit('hide-modal');
            }
        }
    }
</script>

<style>

</style>