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
    import lineChart from '../../../../charts/lineChart';

    import loading from '../../../fragments/loading/Loading.vue';

    import currency from '../../../../filters/currency';

    export default{
        props: [
            'charting-site'
        ],
        components: {
            lineChart,
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
            test() {
                return {
                    labels: ["2017-05-21 02:02:02", "2017-05-22 02:02:02", "2017-05-23 02:02:02", "2017-05-24 02:02:02", "2017-05-25 02:02:02", "2017-05-26 02:02:02", "2017-05-27 02:02:02", "2017-05-28 02:02:02", "2017-05-29 02:02:02", "2017-05-30 02:02:02", "2017-05-31 02:02:02"],
                    datasets: [
                        {
                            label: this.url.domainFullPath,
                            backgroundColor: '#7ed0c0',
                            data:  [31, 21, 30, 29, 32.95, 32.95, 32.95, 32.95, 32.95, 32.95, 32.95,]
                        }
                    ]
                };
            },
            testOptions(){
                return {};
            }
        }
    }
</script>

<style>

</style>