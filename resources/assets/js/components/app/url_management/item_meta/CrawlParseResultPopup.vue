<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Crawl / Parse Result</p>
                <button class="close" @click.prevent="cancelPushingToQueue"><span aria-hidden="true">×</span></button>
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table>

                        </table>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-flat" @click.prevent="pushToQueue" :disabled="isPushingToQueue">PUSH TO QUEUE</button>
                        <button class="btn btn-default btn-flat" @click.prevent="cancelPushingToQueue">CANCEL</button>
                    </div>
                </div>
            </footer>
        </div>
        <loading v-if="isPushingToQueue"></loading>
    </div>
</template>

<script>
    import loading from '../../../Loading.vue';

    export default{
        mounted(){
            console.info('CrawlParseResultPopup component is mounted.');
        },
        components: {
            loading
        },
        props: [
            'is-active',
            'crawl-parse-result'
        ],
        data(){
            return {
                'isPushingToQueue': false,
            }
        },
        method: {
            cancelPushingToQueue(){
                this.$emit('hide-modal');
            },
            pushToQueue(){

            },
            confirmPushToQueue(){
                this.$emit('pushed-to-queue');
            }
        },
        computed: {
            crawlParseResultStatus(){
                return this.crawlParseResult.status;
            },
            crawlParseResultContent(){
                return this.crawlParseResult.results;
            }
        }
    }
</script>

<style>

</style>