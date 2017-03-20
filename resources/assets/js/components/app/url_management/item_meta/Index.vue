<template>
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="row m-b-15">
                            <div class="col-sm-6">
                                Show
                                &nbsp;&nbsp;
                                <select class="form-control sl-form-control input-sm" v-model="paginationData.per_page"
                                        @change="loadItemMetas(currentPageUrl)">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                &nbsp;&nbsp;
                                Item Metas
                            </div>
                            <div class="col-sm-6 text-right">
                                Search
                                &nbsp;&nbsp;
                                <input type="text" class="form-control sl-form-control input-sm" v-model="filterText"
                                       @input="onFilterChanged">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped table-condensed table-paginated">
                                    <thead>
                                    <tr>
                                        <th :class="orderByClass('id')" @click.prevent="setOrdering('id')">ID</th>
                                        <th :class="orderByClass('element')" @click.prevent="setOrdering('name')">Element</th>
                                        <th :class="orderByClass('value')" @click.prevent="setOrdering('value')">Value</th>
                                        <th :class="orderByClass('created_at')" @click.prevent="setOrdering('created_at')">Created at</th>
                                        <th :class="orderByClass('updated_at')" @click.prevent="setOrdering('updated_at')">Updated at</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="itemMetas.length > 0">
                                    <single-item-meta-row v-for="itemMeta in itemMetas" :current-item-meta="itemMeta" :item="item" @reloadItemMetas="loadItemMetas(currentPageUrl)"></single-item-meta-row>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No item meta data available
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="#" class="btn btn-primary btn-sm btn-flat" @click.prevent="showCreateNewItemMeta">CREATE NEW ITEM META</a>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItemMetas" :disabled="paginationData.current_page == 1">FIRST</button>
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItemMetas(prevPageUrl)" :disabled="prevPageUrl == null">PREV</button>
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItemMetas(nextPageUrl)" :disabled="nextPageUrl == null">NEXT</button>
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItemMetas(lastPageUrl)" :disabled="paginationData.current_page == paginationData.last_page">LAST
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <create-popup :is-active="isCreatingNewItemMeta" :item="item" @hide-modal="hideCreatePopup" @created-new-item-meta="createdNewItemMeta"></create-popup>
    </section>
</template>

<script>
    import createPopup from './CreatePopup.vue';
    import singleItemMetaRow from './SingleItemMetaRow.vue';

    export default{

        components: {
            createPopup,
            singleItemMetaRow,
        },
        data(){
            return {
                item: null,
                itemMetas: [],
                paginationData: {
                    current_page: 1,
                    from: null,
                    last_page: null,
                    next_page_url: null,
                    per_page: 25,
                    prev_page_url: null,
                    to: null,
                    total: null,
                },
                orderByData: {
                    column: 'id',
                    direction: 'asc'
                },
                filterText: '',
                filterDelayData: {
                    promise: null,
                    delay: 500
                },
                isCreatingNewItemMeta: false,
            }
        },
        mounted(){
            console.info('Index component mounted.');
            this.initSetItem();
            this.loadItemMetas();
        },
        methods: {
            initSetItem(){
                this.item = editingItem;
            },
            loadItemMetas(link){
                if (typeof link != 'string') {
                    link = this.firstPageUrl;
                }
                axios.get(link).then(response => {
                    if (response.data.status == true) {
                        this.itemMetas = response.data.itemMetas.data;
                        this.paginationData.current_page = response.data.itemMetas.current_page;
                        this.paginationData.from = response.data.itemMetas.from;
                        this.paginationData.last_page = response.data.itemMetas.last_page;
                        this.paginationData.next_page_url = response.data.itemMetas.next_page_url;
                        this.paginationData.per_page = response.data.itemMetas.per_page;
                        this.paginationData.prev_page_url = response.data.itemMetas.prev_page_url;
                        this.paginationData.to = response.data.itemMetas.to;
                        this.paginationData.total = response.data.itemMetas.total;
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            orderByClass(column){
                return this.orderByData.column == column ? 'order-' + this.orderByData.direction : '';
            },
            setOrdering(column){
                if (this.orderByData.column == column) {
                    if (this.orderByData.direction == 'asc') {
                        this.orderByData.direction = 'desc';
                    } else {
                        this.orderByData.direction = 'asc';
                    }
                } else {
                    this.orderByData.column = column;
                    this.orderByData.direction = 'asc'
                }

                this.loadItemMetas(this.currentPageUrl);
            },
            onFilterChanged(){
                if (this.filterDelayData.promise != null) {
                    clearTimeout(this.filterDelayData.promise);
                }
                this.filterDelayData.promise = setTimeout(() => {
                    this.paginationData.current_page = 1;
                    this.loadItemMetas(this.currentPageUrl);
                }, this.filterDelayData.delay);
            },
            hideCreatePopup(){
                this.isCreatingNewItemMeta = false;
            },
            createdNewItemMeta(){
                this.hideCreatePopup();
                this.loadItemMetas();
            },
            showCreateNewItemMeta(){
                this.isCreatingNewItemMeta = true;
            }
        },
        computed: {
            currentPageUrl: function () {
                return this.item.urls.meta_index + '&page=' + this.paginationData.current_page
                        + '&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText;
            },
            nextPageUrl: function () {
                if (this.paginationData.next_page_url == null) {
                    return null;
                } else {
                    return this.paginationData.next_page_url
                            + '&orderBy=' + this.orderByData.column
                            + '&direction=' + this.orderByData.direction
                            + '&per_page=' + this.paginationData.per_page
                            + '&key=' + this.filterText;
                }
            },
            prevPageUrl: function () {
                if (this.paginationData.prev_page_url == null) {
                    return null;
                } else {
                    return this.paginationData.prev_page_url
                            + '&orderBy=' + this.orderByData.column
                            + '&direction=' + this.orderByData.direction
                            + '&per_page=' + this.paginationData.per_page
                            + '&key=' + this.filterText;
                }
            },
            firstPageUrl: function () {
                return this.item.urls.meta_index + '&page=1&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText;
            },
            lastPageUrl: function () {
                return this.item.urls.meta_index + '&page=' + this.paginationData.last_page
                        + '&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText;
            }
        }
    }
</script>

<style>

</style>