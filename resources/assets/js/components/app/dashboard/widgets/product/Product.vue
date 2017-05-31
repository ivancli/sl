<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ widget.name }}</h3>
                    <div class="box-tools pull-right">
                        <!--<div class="btn-group">-->
                            <!--<a class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" href="#">-->
                                <!--<i class="fa fa-download"></i>-->
                            <!--</a>-->
                            <!--<ul class="dropdown-menu" role="menu">-->
                                <!--<li><a href="#">Download PNG</a></li>-->
                                <!--<li><a href="#">Download JPEG</a></li>-->
                                <!--<li><a href="#">Download PDF</a></li>-->
                                <!--<li><a href="#">Download SVG</a></li>-->
                            <!--</ul>-->
                        <!--</div>-->

                        <!--<button type="button" class="btn btn-box-tool btn-edit-widget">-->
                            <!--<i class="fa fa-pencil"></i>-->
                        <!--</button>-->
                        <button type="button" class="btn btn-box-tool" @click.prevent="onClickDeleteChart">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <vue-highcharts v-if="!isLoading" :options="options" ref="lineCharts"></vue-highcharts>
                </div>
            </div>
        </div>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingChart"></loading>
    </div>
</template>

<script>

    import vueHighcharts from 'vue2-highcharts';

    import loading from '../../../../fragments/loading/Loading.vue';

    import deleteConfirmation from '../../../../fragments/modals/DeleteConfirmation.vue';

    export default{
        components: {
            vueHighcharts,
            deleteConfirmation,
        },
        props: [
            'global-timespan',
            'global-resolution',
            'widget',
        ],
        mounted(){
            console.info('Product component mounted.');
            this.initSetParams();
            this.loadChartData();
        },
        watch: {
            globalTimespan(){
                this.initSetParams();
                this.loadChartData();
            },
            globalResolution(){
                this.initSetParams();
                this.loadChartData();
            }
        },
        data(){
            return {
                isLoading: false,
                timespan: 'this_week',
                resolution: 'day',
                prices: [],
                isDeletingChart: true,
                deleteParams: {
                    title: 'Chart',
                    list: [
                        'All Product data associated with this Chart displayed on the Dashboard',
                    ],
                    active: false,
                },
            };
        },
        methods: {
            initSetParams(){
                if (this.globalTimespan !== null && this.globalTimespan !== '') {
                    this.timespan = this.globalTimespan;
                } else {
                    this.timespan = this.widget.timespan;
                }
                if (this.globalResolution !== null && this.globalResolution !== '') {
                    this.resolution = this.globalResolution;
                } else {
                    this.resolution = this.widget.resolution;
                }
            },
            loadChartData(){
                this.isLoading = true;
                axios.get('/chart/product', this.loadChartDataRequestData).then(response => {
                    this.isLoading = false;
                    if (response.data.status === true) {
                        this.prices = response.data.data;
                        setTimeout(() => {
                            this.updateChartSeries();
                        }, 200);
                    }
                }).catch(error => {
                    this.isLoading = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                });
            },
            updateChartSeries(){
                this.prices.forEach((price) => {
                    this.$refs.lineCharts.addSeries({
                        name: price.site,
                        marker: {
                            symbol: 'square'
                        },
                        data: price.data,
                    });
                });
            },
            onClickDeleteChart(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteChart()
            },
            deleteChart(){
                this.isDeletingChart = true;
                axios.delete(this.widget.urls.delete).then(response => {
                    this.isDeletingChart = false;
                    if (response.data.status === true) {
                        this.emitDeleteWidget();
                    }
                }).catch(error => {
                    this.isDeletingChart = false;
                })
            },
            emitDeleteWidget(){
                this.$emit('deleted-widget');
            }
        },
        computed: {
            product(){
                return this.widget.widgetable;
            },
            loadChartDataRequestData(){
                return {
                    params: {
                        id: this.product.id,
                        from: this.from,
                        to: this.to,
                        resolution: this.resolution,
                    }
                }
            },
            options(){
                return {
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: this.product.product_name
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
                        labels: {
                            formatter: function () {
                                return '$' + this.value;
                            }
                        }
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },
                    credits: {
                        enabled: false
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: '#666666',
                                lineWidth: 1
                            }
                        }
                    },
                    series: []
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
        }
    }
</script>

<style>

</style>