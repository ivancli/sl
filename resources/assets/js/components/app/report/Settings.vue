<template>
    <div>
        <div class="row m-b-15">
            <div class="col-sm-6">
                Show
                &nbsp;&nbsp;
                <select class="form-control sl-form-control input-sm" v-model="paginationData.per_page" @change="loadReports(currentPageUrl)">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                &nbsp;&nbsp;
                Reports
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
                        <th>Report source</th>
                        <th>Schedule</th>
                        <th>Last report</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody v-if="reports.length > 0">
                    <single-report-row v-for="report in reports" :current-report="report" @reload-reports="loadReports(currentPageUrl)"></single-report-row>
                    </tbody>
                    <tbody v-else>
                    <tr>
                        <td colspan="7" class="text-center">
                            No report data available
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadReports" :disabled="paginationData.current_page == 1">FIRST</button>
                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadReports(prevPageUrl)" :disabled="prevPageUrl == null">PREV</button>
                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadReports(nextPageUrl)" :disabled="nextPageUrl == null">NEXT</button>
                <button class="btn btn-default btn-sm btn-flat" @click.prevent="loadReports(lastPageUrl)" :disabled="paginationData.current_page == paginationData.last_page || paginationData.total <= paginationData.per_page">LAST</button>
            </div>
        </div>
    </div>
</template>

<script>
    import singleReportRow from './settings/SingleReportRow.vue';

    export default{
        components: {
            singleReportRow,
        },
        data(){
            return {
                reports: [],
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
        mounted(){
            console.info('Settings component mounted.');
            this.loadReports();
        },
        methods: {
            loadReports(link){
                if (typeof link !== 'string') {
                    link = this.firstPageUrl;
                }
                axios.get(link).then(response => {
                    if (response.data.status === true) {
                        this.reports = response.data.reports.data;
                        this.paginationData.current_page = response.data.reports.current_page;
                        this.paginationData.from = response.data.reports.from;
                        this.paginationData.last_page = response.data.reports.last_page;
                        this.paginationData.next_page_url = response.data.reports.next_page_url;
                        this.paginationData.per_page = response.data.reports.per_page;
                        this.paginationData.prev_page_url = response.data.reports.prev_page_url;
                        this.paginationData.to = response.data.reports.to;
                        this.paginationData.total = response.data.reports.total;
                    }
                }).catch(error => {
                    console.info(error.response);
                })
            },
            orderByClass(column){
                return this.orderByData.column === column ? 'order-' + this.orderByData.direction : '';
            },
            setOrdering(column){
                if (this.orderByData.column === column) {
                    if (this.orderByData.direction === 'asc') {
                        this.orderByData.direction = 'desc';
                    } else {
                        this.orderByData.direction = 'asc';
                    }
                } else {
                    this.orderByData.column = column;
                    this.orderByData.direction = 'asc'
                }

                this.loadReports(this.currentPageUrl);
            },
            onFilterChanged(){
                if (this.filterDelayData.promise !== null) {
                    clearTimeout(this.filterDelayData.promise);
                }
                this.filterDelayData.promise = setTimeout(() => {
                    this.paginationData.current_page = 1;
                    this.loadReports(this.currentPageUrl);
                }, this.filterDelayData.delay);
            }
        },
        computed: {
            currentPageUrl: function () {
                return '/report?page=' + this.paginationData.current_page
                    + '&orderBy=' + this.orderByData.column
                    + '&direction=' + this.orderByData.direction
                    + '&per_page=' + this.paginationData.per_page
                    + '&key=' + this.filterText;
            },
            nextPageUrl: function () {
                if (this.paginationData.next_page_url === null) {
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
                if (this.paginationData.prev_page_url === null) {
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
                return '/report?page=1&orderBy=' + this.orderByData.column
                    + '&direction=' + this.orderByData.direction
                    + '&per_page=' + this.paginationData.per_page
                    + '&key=' + this.filterText;
            },
            lastPageUrl: function () {
                return '/report?page=' + this.paginationData.last_page
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