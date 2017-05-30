<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                {{ url.domainFullPath }}
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        Content here
                        <line-chart :data="test" :options="testOptions"></line-chart>
                        <vue-highcharts :options="options" ref="lineCharts"></vue-highcharts>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import vueHighcharts from 'vue2-highcharts';

    import loading from '../../../fragments/loading/Loading.vue';

    import currency from '../../../../filters/currency';

    const asyncData = {
        name: 'Tokyo',
        marker: {
            symbol: 'square'
        },
        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, {
            y: 26.5,
            marker: {
                symbol: 'url(http://www.highcharts.com/demo/gfx/sun.png)'
            }
        }, 23.3, 18.3, 13.9, 9.6]
    }

    export default{
        props: [
            'charting-site'
        ],
        components: {
            vueHighcharts,
            loading
        },
        data(){
            return {
                prices: [],
                labels: [],
            }
        },
        mounted(){
            console.info('SiteChart.vue is mounted.');
            this.loadPrices();


            let lineCharts = this.$refs.lineCharts;
            lineCharts.delegateMethod('showLoading', 'Loading...');
            setTimeout(() => {
                lineCharts.addSeries(asyncData);
                lineCharts.hideLoading();
            }, 2000)
        },
        methods: {
            loadPrices(){
                axios.get(this.item.urls.price).then(response => {
                    if (response.data.status === true) {
                        response.data.historicalPrices.forEach(historicalPrice => {
                            this.prices.push(parseFloat(historicalPrice.amount));
                            this.labels.push(historicalPrice.created_at);
                        });
                    }
                }).catch(error => {
                    console.info(error.response);
                });
            },
            hideModal(){
                this.emitHideModal();
            },
            emitHideModal(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            site(){
                return this.chartingSite;
            },
            url(){
                return this.site.url;
            },
            item(){
                return this.site.item;
            },
            options(){
                return {
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: 'Monthly Average Temperature'
                    },
                    subtitle: {
                        text: 'Source: WorldClimate.com'
                    },
                    xAxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yAxis: {
                        title: {
                            text: 'Temperature'
                        },
                        labels: {
                            formatter: function () {
                                return this.value + '°';
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
            }
        }
    }
</script>

<style>

</style>