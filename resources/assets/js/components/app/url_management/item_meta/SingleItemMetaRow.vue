<template>
    <tr>
        <td v-text="itemMeta.id"></td>
        <td v-text="itemMeta.element"></td>
        <td v-text="itemMeta.value"></td>
        <td>{{ itemMeta.format_type ? itemMeta.format_type : "text" | capitalise }}</td>
        <td>{{ itemMeta.historical_type | capitalise }}</td>
        <td>{{ itemMeta.is_supportive == 'y' ? 'true' : 'false' }}</td>
        <td>{{ itemMeta.status }}</td>
        <td>{{ itemMeta.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ itemMeta.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="itemMeta.urls.show" class="text-muted" title="view">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a href="#" class="text-muted" title="edit" @click.prevent="onClickEditItemMeta">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a href="#" class="text-muted" title="edit configurations" @click.prevent="onClickEditItemMetaConfs">
                <i class="glyphicon glyphicon-cog"></i>
            </a>
            <a href="#" class="text-muted" title="test crawl/parse" @click.prevent="onClickTestCrawlParseItemMeta" v-if="itemMeta.is_supportive == 'n'">
                &nbsp;
                <i class="glyphicon glyphicon-refresh"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteItemMeta" title="delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <edit-popup :is-active="isEditingItemMeta" :item-meta="itemMeta" @hide-modal="hideEditPopup" @updated-item-meta="updatedItemMeta"></edit-popup>
        <edit-confs-popup :is-active="isEditingItemMetaConfs" :item-meta="itemMeta" @hide-modal="hideEditItemMetaConfsPopup" @updated-item-meta-confs="updatedItemMetaConfs"></edit-confs-popup>
        <crawl-parse-result-popup v-if="crawlParseResult != null" :item-meta="itemMeta" :crawl-parse-result="crawlParseResult" @hide-modal="hideCrawlParseResultPopup"
                                  @pushed-to-queue="pushedToQueue"></crawl-parse-result-popup>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingItemMeta || isTestingCrawlParseItemMeta"></loading>
    </tr>
</template>

<script>
    import editPopup from './EditPopup.vue';
    import editConfsPopup from '../item_meta_conf/EditPopup.vue';
    import crawlParseResultPopup from './CrawlParseResultPopup.vue';

    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';
    import capitalise from '../../../../filters/capitalise';

    export default{
        components: {
            editPopup,
            editConfsPopup,
            crawlParseResultPopup,
            deleteConfirmation,
            loading,
        },
        data(){
            return {
                deleteParams: {
                    title: 'item meta',
                    list: [
                        'All configuration associated with this meta',
                        'All historical prices associated with this meta',
                    ],
                    active: false
                },
                crawlParseResult: null,
                isDeletingItemMeta: false,
                isEditingItemMeta: false,
                isEditingItemMetaConfs: false,
                isTestingCrawlParseItemMeta: false,
            }
        },
        props: [
            'current-item-meta',
            'item'
        ],
        mounted(){
            console.info('SingleItemMetaRow component mounted.');
        },
        computed: {
            itemMeta(){
                return this.currentItemMeta;
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
            onClickEditItemMeta(){
                this.isEditingItemMeta = true;
            },
            hideEditPopup(){
                this.isEditingItemMeta = false;
            },
            updatedItemMeta(){
                this.$emit('reloadItemMetas');
                this.hideEditPopup();
            },
            onClickEditItemMetaConfs(){
                this.isEditingItemMetaConfs = true;
            },
            hideEditItemMetaConfsPopup(){
                this.isEditingItemMetaConfs = false;
            },
            updatedItemMetaConfs(){
                this.hideEditItemMetaConfsPopup();
            },
            onClickDeleteItemMeta(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteItemMeta()
            },
            deleteItemMeta(){
                this.isDeletingItemMeta = true;
                axios.delete(this.itemMeta.urls.delete).then(response => {
                    this.isDeletingItemMeta = false;
                    if (response.data.status == true) {
                        this.$emit('reloadItemMetas');
                    }
                }).catch(error => {
                    this.isDeletingItemMeta = false;
                })
            },
            onClickTestCrawlParseItemMeta(){
                this.testCrawlParseItemMeta();
            },
            testCrawlParseItemMeta(){
                this.isTestingCrawlParseItemMeta = true;
                axios.post(this.itemMeta.urls.test_crawl_parse).then(response => {
                    this.isTestingCrawlParseItemMeta = false;
                    this.crawlParseResult = response.data;
                }).catch(error => {
                    this.isTestingCrawlParseItemMeta = false;
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