<template>
    <tr>
        <td v-text="item.id"></td>
        <td v-text="item.name"></td>
        <td v-text="item.is_active"></td>
        <td>{{ item.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ item.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="item.urls.show" class="text-muted" title="view">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a href="#" class="text-muted" title="edit" @click.prevent="onClickEditItem">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a :href="item.urls.meta_index" class="text-muted" title="edit meta">
                <i class="glyphicon glyphicon-qrcode"></i>
            </a>
            &nbsp;
            <a href="#" class="text-muted" title="test crawl/parse" @click.prevent="onClickTestCrawlParseItem">
                <i class="glyphicon glyphicon-refresh"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteItem" title="delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <edit-popup :is-active="isEditingItem" :item="item" @hide-modal="hideEditPopup" @updated-item="updatedItem"></edit-popup>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <crawl-parse-result-popup v-if="crawlParseResult != null" :item="item" :crawl-parse-result="crawlParseResult" @hide-modal="hideCrawlParseResultPopup" @pushed-to-queue="pushedToQueue"></crawl-parse-result-popup>
        <loading v-if="isDeletingItem || isTestingCrawlParseItem"></loading>
    </tr>
</template>

<script>
    import editPopup from './EditPopup.vue';
    import crawlParseResultPopup from './CrawlParseResultPopup.vue';

    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';

    export default{
        components: {
            editPopup,
            deleteConfirmation,
            crawlParseResultPopup,
            loading,
        },
        data(){
            return {
                deleteParams: {
                    title: 'item',
                    list: [
                        'All historic prices associated with this item',
                        'All meta data associated with this item',
                    ],
                    active: false
                },
                isDeletingItem: false,
                isEditingItem: false,
                isTestingCrawlParseItem: false,
                crawlParseResult: null,
            }
        },
        props: [
            'current-item',
            'url'
        ],
        mounted(){
            console.info('SingleItemRow component mounted.');
        },
        computed: {
            item(){
                return this.currentItem;
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
        },
        methods: {
            onClickEditItem(){
                this.isEditingItem = true;
            },
            hideEditPopup(){
                this.isEditingItem = false;
            },
            updatedItem(){
                this.$emit('reloadItems');
                this.hideEditPopup();
            },
            onClickDeleteItem(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteItem()
            },
            deleteItem(){
                this.isDeletingItem = true;
                axios.delete(this.item.urls.delete).then(response => {
                    this.isDeletingItem = false;
                    if (response.data.status == true) {
                        this.$emit('reloadItems');
                    }
                }).catch(error => {
                    this.isDeletingItem = false;
                })
            },
            onClickTestCrawlParseItem(){
                this.testCrawlParseItemMeta();
            },
            testCrawlParseItemMeta()
            {
                this.isTestingCrawlParseItem = true;
                axios.post(this.item.urls.test_crawl_parse).then(response => {
                    this.isTestingCrawlParseItem = false;
                    this.crawlParseResult = response.data;
                }).catch(error => {
                    this.isTestingCrawlParseItem = false;
                })
            },
            pushedToQueue(){
                this.hideCrawlParseResultPopup();
                /*reload this item*/
            },
            hideCrawlParseResultPopup(){
                this.crawlParseResult = null;
            }
        }
    }
</script>

<style>

</style>