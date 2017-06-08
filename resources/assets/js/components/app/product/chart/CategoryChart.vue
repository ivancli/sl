<template>
    <div class="modal is-active chart-modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                {{ category.category_name }}
                <a class="close" @click.prevent="hideModal">&times;</a>
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                                    <li v-for="(error,index) in errors">
                                        <div v-if="error.constructor != Array" v-text="error"></div>
                                        <div v-else v-for="message in error">
                                            <div v-if="index == 'id'">
                                                please select a {{ type }}
                                            </div>
                                            <div v-else v-text="message"></div>
                                        </div>
                                    </li>
                                </ul>
                                <ul class="p-b-10 p-l-20 success-container" v-if="successMsg != ''">
                                    <li v-text="successMsg"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 chart-options">
                                Generate a chart for
                                <select class="input-sm form-control sl-form-control" v-model="timespan">
                                    <option value="this_week">this week</option>
                                    <option value="last_week">last week</option>
                                    <option value="last_7_days">last 7 days</option>
                                    <option value="this_month">this month</option>
                                    <option value="last_month">last month</option>
                                    <option value="last_30_days">last 30 days</option>
                                    <option value="this_quarter">this quarter</option>
                                    <option value="last_quarter">last quarter</option>
                                    <option value="last_90_days">last 90 days</option>
                                </select>
                                showing a price for each
                                <select class="input-sm form-control sl-form-control" v-model="resolution">
                                    <option value="day">day</option>
                                    <option value="week">week</option>
                                    <option value="month">month</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" v-model="showAddToDashboardOptions">
                                        I would like to add this chart to my dashboard.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="showAddToDashboardOptions">
                            <div class="col-sm-12 m-b-20">
                                Name this chart <input type="text" class="input-sm form-control sl-form-control" placeholder="enter a chart name" v-model="name">
                            </div>
                            <div class="col-sm-12">
                                <a class="btn btn-primary btn-flat" href="#" @click.prevent="addWidget">ADD CHART</a>
                                <a class="btn btn-default btn-flat" href="#" @click.prevent="hideAddToDashboardOptions">CANCEL</a>
                            </div>
                        </div>
                        <div class="row" v-if="!showAddToDashboardOptions">
                            <div class="col-sm-12">
                                <a class="btn btn-primary btn-flat" href="#" @click.prevent="loadPrices">GO</a>
                                <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <vue-highcharts v-if="!isLoading" :options="options" ref="lineCharts"></vue-highcharts>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import vueHighcharts from 'vue2-highcharts';

    import loading from '../../../fragments/loading/Loading.vue';

    import currency from '../../../../filters/currency';

    export default{
        props: [
            'charting-category'
        ],
        components: {
            vueHighcharts,
            loading
        },
        data(){
            return {
                timespan: 'this_week',
                resolution: 'day',
                name: null,
                prices: [],
                showAddToDashboardOptions: false,
                isLoading: false,
                errors: {},
                successMsg: ''
            }
        },
        mounted(){
            console.info('CategoryChart.vue is mounted.');
            this.loadPrices();
        },
        methods: {
            initChart(){
                this.showLoading();
            },
            loadPrices(){
                this.isLoading = true;
                axios.get('/chart/category', this.loadPricesRequestData).then(response => {
                    this.isLoading = false;
                    if (response.data.status === true) {
                        this.prices = response.data.data;
                        setTimeout(() => {
                            this.updateChartSeries();
                        }, 200);
                    }
                }).catch(error => {
                    this.isLoading = false;
                    console.info(error.response);
                });
            },
            addWidget(){
                axios.post('dashboard/widget', this.addWidgetRequestData).then(response => {
                    this.isAddingNewWidget = false;
                    if (response.data.status === true) {
                        this.successMsg = 'The chart has been added to Dashboard.';
                        this.showAddToDashboardOptions = false;
                    }
                }).catch(error => {
                    this.isAddingNewWidget = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            updateChartSeries(){
                this.prices.forEach((product) => {
                    /*TODO fixed the arearange series*/
//                    this.$refs.lineCharts.addSeries({
//                        name: product.name,
//                        type: 'areasplinerange',
//                        fillOpacity: 0.3,
//                        zIndex: 0,
//                        data: product.range,
//                        marker: {
//                            enabled: true
//                        }
//                    });
                    this.$refs.lineCharts.addSeries({
                        name: product.name + ' average',
                        zIndex: 1,
                        marker: {
                            symbol: 'square'
                        },
                        data: product.average,
                    });
                });
            },
            showLoading(){
                this.$refs.lineCharts.delegateMethod('showLoading', 'Loading...');
            },
            hideLoading(){
                this.$refs.lineCharts.hideLoading();
            },
            hideAddToDashboardOptions(){
                this.showAddToDashboardOptions = false;
            },
            hideModal(){
                this.emitHideModal();
            },
            emitHideModal(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            category(){
                return this.chartingCategory;
            },
            addWidgetRequestData(){
                return {
                    id: this.category.id,
                    type: 'category',
                    timespan: this.timespan,
                    resolution: this.resolution,
                    name: this.name,
                }
            },
            from(){
                let startDate = null;
                switch (this.timespan) {
                    case "this_week":
                        startDate = moment().startOf('isoweek').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_week":
                        startDate = moment().subtract(1, 'week').startOf('isoweek').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_7_days":
                        startDate = moment().subtract(7, 'day').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_month":
                        startDate = moment().startOf("month").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_month":
                        startDate = moment().subtract(1, 'month').startOf("month").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_30_days":
                        startDate = moment().subtract(30, 'day').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_quarter":
                        startDate = moment().startOf("quarter").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_quarter":
                        startDate = moment().subtract(1, 'quarter').startOf("quarter").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_90_days":
                        startDate = moment().subtract(90, 'day').format("YYYY-MM-DD HH:mm:ss");
                        break;
                }
                return startDate;
            },
            to(){
                let endDate = null;
                switch (this.timespan) {
                    case "this_week":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_week":
                        endDate = moment().subtract(1, 'week').endOf('isoweek').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_7_days":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_month":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_month":
                        endDate = moment().subtract(1, 'month').endOf("month").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_30_days":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_quarter":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_quarter":
                        endDate = moment().subtract(1, 'quarter').endOf("quarter").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_90_days":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                }
                return endDate;
            },
            loadPricesRequestData()
            {
                return {
                    params: {
                        id: this.category.id,
                        from: this.from,
                        to: this.to,
                        resolution: this.resolution,
                    }
                }
            },
            options(){
                return {
                    title: {
                        text: this.category.category_name,
                    },
                    subtitle: {
                        text: 'Average Price Chart by ' + this.resolution,
                    },
                    xAxis: {
                        type: "datetime",
                        labels: {
                            format: '{value:%e %b}'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'price'
                        },
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },
                    credits: {
                        enabled: false
                    },
                    series: []
                }
            }
        }
    }
</script>

<style>
    header.modal-card-head .close {
        margin-left: auto;
    }

    @media screen and (min-width: 1201px) {
        .chart-modal .modal-content,
        .chart-modal .modal-card {
            width: 1200px;
        }
    }

    .chart-options {
        line-height: 40px;
    }
</style>