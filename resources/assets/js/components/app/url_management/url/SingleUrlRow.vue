<template>
    <tr :class="urlRowColour">
        <td v-text="url.id"></td>
        <td class="word-wrap-break-word word-break-break-all">
            <a :href="url.full_path" v-text="url.full_path" target="_blank"></a>
        </td>
        <td>{{ url.status }}</td>
        <td>{{ url.failMetasCount }}</td>
        <td>
            <div v-if="url.crawler.crawled_at != null">
                {{ url.crawler.crawled_at | formatDateTime(datetimeFormat) }}
            </div>
            <div v-else></div>
        </td>
        <td>{{ url.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ url.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="url.urls.show" class="text-muted" title="view">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a :href="url.urls.edit" class="text-muted" title="edit">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a :href="url.urls.item_index" class="text-muted" title="edit items">
                <i class="glyphicon glyphicon-qrcode"></i>
            </a>
            &nbsp;
            <a href="#" class="text-muted" title="test crawl/parse" @click.prevent="onClickTestCrawlParseUrl">
                <i class="glyphicon glyphicon-refresh"></i>
            </a>
            <a href="#" class="text-muted" title="assign item to referencing sites" v-if="url.sitesNoItemCount > 0 && url.itemsCount == 1" @click.prevent="assignItemToSites">
                &nbsp;
                <i class="glyphicon glyphicon-hand-down"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteUrl" title="delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <crawl-parse-result-popup v-if="crawlParseResult != null" :url="url" :crawl-parse-result="crawlParseResult" @hide-modal="hideCrawlParseResultPopup"
                                  @pushed-to-queue="pushedToQueue"></crawl-parse-result-popup>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete"
                             @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingUrl || isTestingCrawlParseUrl || isAssigningItemToSites"></loading>
    </tr>
</template>

<script>
    import crawlParseResultPopup from './CrawlParseResultPopup.vue';

    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../fragments/loading/Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';
    import capitalise from '../../../../filters/capitalise';

    export default{
        components: {
            deleteConfirmation,
            crawlParseResultPopup,
            loading,
        },
        props: [
            'current-url'
        ],
        data(){
            return {
                deleteParams: {
                    title: 'URL',
                    list: [
                        'All URL related categories, products and URLs',
                        'All URL related pricing information',
                    ],
                    active: false
                },
                crawlParseResult: null,
                isDeletingUrl: false,
                isTestingCrawlParseUrl: false,
                isAssigningItemToSites: false,
            };
        },
        mounted(){
            console.info('SingleUrlRow component is mounted.');
        },
        methods: {
            onClickDeleteUrl(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteUrl()
            },
            deleteUrl(){
                this.isDeletingUrl = true;
                axios.delete(this.url.urls.delete).then(response => {
                    this.isDeletingUrl = false;
                    if (response.data.status == true) {
                        this.$emit('reloadUrls');
                    }
                }).catch(error => {
                    this.isDeletingUrl = false;
                })
            },
            onClickTestCrawlParseUrl(){
                this.testCrawlParseUrl();
            },
            testCrawlParseUrl(){
                this.isTestingCrawlParseUrl = true;
                axios.post(this.url.urls.test_crawl_parse).then(response => {
                    this.isTestingCrawlParseUrl = false;
                    this.crawlParseResult = response.data;
                }).catch(error => {
                    this.isTestingCrawlParseUrl = false;
                });
            },
            pushedToQueue(){
                this.hideCrawlParseResultPopup();
                /*reload this item*/
            },
            hideCrawlParseResultPopup(){
                this.crawlParseResult = null;
            },
            assignItemToSites(){
                this.isAssigningItemToSites = true;
                axios.post(this.url.urls.assign).then(response => {
                    this.isAssigningItemToSites = false;
                    this.$emit('reloadUrls');
                }).catch(error => {
                    this.isAssigningItemToSites = false;
                });
            }
        },
        computed: {
            url(){
                return this.currentUrl;
            },
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
            },
            urlRowColour(){
                if (this.url.failMetasCount == 1) {
                    return 'warning';
                } else if (this.url.failMetasCount > 1) {
                    return 'danger';
                } else {
                    return '';
                }
            }
        }
    }
</script>

<style>

</style>