<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <table class="table table-bordered table-striped table-condensed table-hover">
                    <tbody>
                    <tr v-for="(val, prop) in domain">
                        <th v-text="prop"></th>
                        <td>
                            <div v-if="typeof val == 'object' || typeof val == 'array'">
                                <table class="table table-bordered table-striped table-condensed table-hover">
                                    <tbody>
                                    <tr v-for="(child_val, child_prop) in val">
                                        <th v-text="child_prop"></th>
                                        <td>
                                            <div v-if="typeof child_val == 'object' || typeof child_val == 'array'">
                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                    <tbody>
                                                    <tr v-for="(nested_val, nested_prop) in child_val">
                                                        <th v-text="nested_prop"></th>
                                                        <td v-text="nested_val"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-else v-text="child_val"></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else v-text="val"></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="row m-t-20">
                    <div class="col-sm-12 text-right">
                        <a :href="domain.modelUrls.edit" class="btn btn-primary btn-sm btn-flat" v-if="domain.modelUrls">EDIT</a>
                        <a href="/url-management/domain" class="btn btn-default btn-sm btn-flat">BACK</a>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="isLoadingDomain"></loading>
    </section>
</template>

<script>
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading
        },
        data(){
            return {
                domain: {},
                isLoadingDomain: false,
            }
        },
        mounted() {
            console.info('Show component is mounted.');
            this.loadDomain();
        },
        methods: {
            loadDomain(){
                this.isLoadingDomain = true;
                axios.get(showingDomain.modelUrls.show).then(response=> {
                    this.isLoadingDomain = false;
                    if (response.data.status == true) {
                        this.domain = response.data.domain;
                    }
                }).catch(error=> {
                    this.isLoadingDomain = false;
                })
            },
        },
        computed: {}
    }
</script>

<style>
</style>
