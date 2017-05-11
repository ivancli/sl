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
    import loading from '../../../fragments/loading/Loading.vue';

    import currency from '../../../../filters/currency';

    export default{
        props: [
            'charting-site'
        ],
        components: {
            loading
        },
        data(){
            return {}
        },
        mounted(){
            console.info('SiteChart.vue is mounted.');
            this.loadPrices();
        },
        methods: {
            loadPrices(){
                axios.get(this.item.urls.price).then(response => {
                    if (response.data.status == true) {
                        console.info(response.data.historicalPrices);
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
            }
        }
    }
</script>

<style>

</style>