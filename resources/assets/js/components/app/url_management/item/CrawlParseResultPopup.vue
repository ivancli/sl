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
                        <table class="table table-bordered">
                            <tbody>
                            <tr v-for="(result, index) in crawlParseResultContent">
                                <td v-text="index"></td>
                                <td>
                                    <div v-if="typeof result == 'object' || typeof result == 'array'">
                                    </div>
                                    <div v-else v-text="result"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-flat" @click.prevent="pushToQueue"
                                :disabled="isPushingToQueue">PUSH TO QUEUE
                        </button>
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
            'item',
            'crawl-parse-result'
        ],
        data(){
            return {
                'isPushingToQueue': false,
            }
        },
        methods: {
            cancelPushingToQueue(){
                this.$emit('hide-modal');
            },
            pushToQueue(){
                this.isPushingToQueue = true;
                axios.post(this.item.urls.queue).then(response => {
                    this.isPushingToQueue = false;
                    if (response.data.status == true) {
                        this.confirmPushToQueue();
                    }
                }).catch(error => {
                    this.isPushingToQueue = false;
                })
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