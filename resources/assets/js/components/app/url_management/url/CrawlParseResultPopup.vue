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
                                    <div>
                                        <table class="table table-bordered table-striped table-condensed table-hover">
                                            <tbody>
                                            <tr>
                                                <th>
                                                    <div v-if="result.item.name == null">{{ result.item.id }}</div>
                                                    <div v-else>{{ result.item.name }}</div>
                                                </th>
                                                <td>
                                                    <div v-if="typeof result.results == 'object' || typeof result.results == 'array'">
                                                        <table class="table table-bordered table-striped table-condensed table-hover">
                                                            <tbody>
                                                            <tr v-for="item_result in result.results">
                                                                <th v-text="item_result.item_meta.element"></th>
                                                                <td>
                                                                    <div v-if="typeof item_result.results == 'object' || typeof item_result.results == 'array'">
                                                                        <table class="table table-bordered table-striped table-condensed table-hover">
                                                                            <tbody>
                                                                            <tr v-for="(item_meta_result, item_meta_index) in item_result.results">
                                                                                <th v-text="item_meta_index"></th>
                                                                                <td v-text="item_meta_result"></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div v-else v-text="result.results"></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
            'url',
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
                axios.post(this.url.urls.queue).then(response => {
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