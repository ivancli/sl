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
                        <table class="table table-bordered" v-if="crawlParseResultStatus == true">
                            <tbody>
                            <tr v-for="(result, index) in crawlParseResultContent">
                                <td v-text="index"></td>
                                <td>
                                    <div v-if="typeof result == 'object' || typeof result == 'array'">
                                        <table class="table table-bordered table-striped table-condensed table-hover">
                                            <tbody>
                                            <tr v-for="(nested_val, nested_prop) in result">
                                                <th v-text="nested_prop"></th>
                                                <td>
                                                    <div v-if="typeof nested_val == 'object' || typeof nested_val == 'array'">
                                                        <table class="table table-bordered table-striped table-condensed table-hover">
                                                            <tbody>
                                                            <tr v-for="(innerNested_val, innerNested_prop) in nested_val">
                                                                <th v-text="innerNested_prop"></th>
                                                                <td v-text="innerNested_val"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div v-else v-text="nested_val"></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else v-text="result"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            <p v-if="crawlParseResultError != null" v-text="crawlParseResultError"></p>
                            <p v-else>Crawl and parse unsuccessful. Unable to catch error. Please contact crawler manager for detailed information.</p>
                        </div>
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
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        mounted(){
            console.info('CrawlParseResultPopup component is mounted.');
        },
        components: {
            loading
        },
        props: [
            'is-active',
            'item-meta',
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
                axios.post(this.itemMeta.urls.queue).then(response => {
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
            },
            crawlParseResultError(){
                if (typeof this.crawlParseResult.error != 'undefined') {
                    return this.crawlParseResult.error;
                } else {
                    return null;
                }
            }
        }
    }
</script>

<style>

</style>