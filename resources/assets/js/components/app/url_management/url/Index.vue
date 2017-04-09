<template>
    <!-- Main content -->
    <section class="content">

        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row m-b-15">
                    <div class="col-sm-6">
                        Show
                        &nbsp;&nbsp;
                        <select class="form-control sl-form-control input-sm" v-model="paginationData.per_page" @change="loadUrls(currentPageUrl)">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        &nbsp;&nbsp;
                        URLs
                    </div>
                    <div class="col-sm-6 text-right">
                        Search
                        &nbsp;&nbsp;
                        <input type="text" class="form-control sl-form-control input-sm" v-model="filterText" @input="onFilterChanged">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover table-striped table-condensed table-paginated">
                            <thead>
                            <tr>
                                <th :class="orderByClass('id')" @click.prevent="setOrdering('id')">ID</th>
                                <th :class="orderByClass('full_path')" @click.prevent="setOrdering('full_path')">Full path</th>
                                <th :class="orderByClass('status')" @click.prevent="setOrdering('status')">Status</th>
                                <th :class="orderByClass('created_at')" @click.prevent="setOrdering('created_at')">Created at</th>
                                <th :class="orderByClass('updated_at')" @click.prevent="setOrdering('updated_at')">Updated at</th>
                                <th width="150"></th>
                            </tr>
                            </thead>
                            <tbody v-if="urls.length > 0">
                            <single-url-row v-for="url in urls" :current-url="url" @reloadUrls="loadUrls(currentPageUrl)"></single-url-row>
                            </tbody>
                            <tbody v-else>
                            <tr>
                                <td colspan="7" class="text-center">
                                    No URL data available
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <a href="/url-management/url/create" class="btn btn-primary btn-sm btn-flat">CREATE NEW URL</a>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadUrls" :disabled="paginationData.current_page == 1">FIRST</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadUrls(prevPageUrl)" :disabled="prevPageUrl == null">PREV</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadUrls(nextPageUrl)" :disabled="nextPageUrl == null">NEXT</button>
                        <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadUrls(lastPageUrl)" :disabled="paginationData.current_page == paginationData.last_page">LAST</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import singleUrlRow from './SingleUrlRow.vue';

    export default {
        components: {
            singleUrlRow
        },
        data(){
            return {
                urls: [],
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
            }
        },
        mounted() {
            console.info('Index component is mounted');
            this.loadUrls();
        },
        methods: {
            loadUrls(link){
                if (typeof link != 'string') {
                    link = this.firstPageUrl;
                }
                axios.get(link).then(response=> {
                    if (response.data.status == true) {
                        this.urls = response.data.urls.data;
                        this.paginationData.current_page = response.data.urls.current_page;
                        this.paginationData.from = response.data.urls.from;
                        this.paginationData.last_page = response.data.urls.last_page;
                        this.paginationData.next_page_url = response.data.urls.next_page_url;
                        this.paginationData.per_page = response.data.urls.per_page;
                        this.paginationData.prev_page_url = response.data.urls.prev_page_url;
                        this.paginationData.to = response.data.urls.to;
                        this.paginationData.total = response.data.urls.total;
                    }
                }).catch(error=> {
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

                this.loadUrls(this.currentPageUrl);
            },
            onFilterChanged(){
                if (this.filterDelayData.promise != null) {
                    clearTimeout(this.filterDelayData.promise);
                }
                this.filterDelayData.promise = setTimeout(()=> {
                    this.paginationData.current_page = 1;
                    this.loadUrls(this.currentPageUrl);
                }, this.filterDelayData.delay);
            }
        },
        computed: {
            currentPageUrl: function () {
                return '/url-management/url?page=' + this.paginationData.current_page
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
                return '/url-management/url?page=1&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText;
            },
            lastPageUrl: function () {
                return '/url-management/url?page=' + this.paginationData.last_page
                        + '&orderBy=' + this.orderByData.column
                        + '&direction=' + this.orderByData.direction
                        + '&per_page=' + this.paginationData.per_page
                        + '&key=' + this.filterText;
            }
        }
    }
</script>

<style>

    table.table-paginated th {
        padding-right: 20px !important;
        cursor: pointer;
        position: relative;
    }

    .table-paginated th.order-asc:after {
        content: "\F160";
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        right: 5px;
        position: absolute;
        color: #aaa;
        top: 50%;
        margin-top: -7px;
    }

    .table-paginated th.order-desc:after {
        content: "\F161";
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        right: 5px;
        position: absolute;
        color: #aaa;
        top: 50%;
        margin-top: -7px;
    }
</style>