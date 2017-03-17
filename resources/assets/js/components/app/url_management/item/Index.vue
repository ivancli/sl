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
                                        @change="loadItems(currentPageUrl)">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                &nbsp;&nbsp;
                                Items
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
                                        <th :class="orderByClass('name')" @click.prevent="setOrdering('name')">Name</th>
                                        <th :class="orderByClass('is_active')" @click.prevent="setOrdering('is_active')">
                                            Is active
                                        </th>
                                        <th :class="orderByClass('created_at')" @click.prevent="setOrdering('created_at')">
                                            Created at
                                        </th>
                                        <th :class="orderByClass('updated_at')" @click.prevent="setOrdering('updated_at')">
                                            Updated at
                                        </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="items.length > 0">
                                    <single-item-row v-for="item in items" :current-item="item" :url="url" @reloadItems="loadItems(currentPageUrl)"></single-item-row>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No item data available
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="#" class="btn btn-primary btn-sm btn-flat" @click.prevent="showCreateNewItem">CREATE NEW ITEM</a>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItems" :disabled="paginationData.current_page == 1">FIRST</button>
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItems(prevPageUrl)" :disabled="prevPageUrl == null">PREV</button>
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItems(nextPageUrl)" :disabled="nextPageUrl == null">NEXT</button>
                                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadItems(lastPageUrl)" :disabled="paginationData.current_page == paginationData.last_page">LAST
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <create-popup :is-active="isCreatingNewItem" :url="url" @hide-modal="hideCreatePopup" @created-new-item="createdNewItem"></create-popup>
    </section>
</template>

<script>
    import createPopup from './CreatePopup.vue';
    import singleItemRow from './SingleItemRow.vue';

    export default{
        components: {
            createPopup,
            singleItemRow,
        },
        data(){
            return {
                url: null,
                items: [],
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
                isCreatingNewItem: false,
            }
        },
        mounted(){
            console.info('Index component mounted.');
            this.initSetUrl();
            this.loadItems();
        },
        methods: {
            initSetUrl(){
                this.url = editingUrl;
            },
            loadItems(link){
                if (typeof link != 'string') {
                    link = this.firstPageUrl;
                }
                axios.get(link).then(response => {
                    if (response.data.status == true) {
                        this.items = response.data.items.data;
                        this.paginationData.current_page = response.data.items.current_page;
                        this.paginationData.from = response.data.items.from;
                        this.paginationData.last_page = response.data.items.last_page;
                        this.paginationData.next_page_url = response.data.items.next_page_url;
                        this.paginationData.per_page = response.data.items.per_page;
                        this.paginationData.prev_page_url = response.data.items.prev_page_url;
                        this.paginationData.to = response.data.items.to;
                        this.paginationData.total = response.data.items.total;
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

                this.loadItems(this.currentPageUrl);
            },
            onFilterChanged(){
                if (this.filterDelayData.promise != null) {
                    clearTimeout(this.filterDelayData.promise);
                }
                this.filterDelayData.promise = setTimeout(() => {
                    this.paginationData.current_page = 1;
                    this.loadItems(this.currentPageUrl);
                }, this.filterDelayData.delay);
            },
            hideCreatePopup(){
                this.isCreatingNewItem = false;
            },
            createdNewItem(){
                this.hideCreatePopup();
                this.loadItems();
            },
            showCreateNewItem(){
                this.isCreatingNewItem = true;
            }
        },
        computed: {
            currentPageUrl: function () {
                return this.url.urls.item_index + '&page=' + this.paginationData.current_page
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
                return this.url.urls.item_index + '&page=1&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText;
            },
            lastPageUrl: function () {
                return this.url.urls.item_index + '&page=' + this.paginationData.last_page
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