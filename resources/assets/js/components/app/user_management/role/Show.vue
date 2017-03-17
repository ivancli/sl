<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <table class="table table-bordered table-striped table-condensed table-hover">
                    <tbody>
                    <tr v-for="(val, prop) in role">
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
                                                        <td>
                                                            <div v-if="typeof nested_val == 'object' || typeof nested_val == 'array'">
                                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                                    <tbody>
                                                                    <tr v-for="(innerNested_val, innerNested_prop) in nested_val">
                                                                        <th v-text="innerNested_prop"></th>
                                                                        <td v-text="innerNested_val"></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div v-else v-text="nested_val"></div>
                                                        </td>
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
                        <a :href="role.urls.edit" class="btn btn-primary btn-sm btn-flat" v-if="role.urls">EDIT</a>
                        <a href="/user-management/role" class="btn btn-default btn-sm btn-flat">BACK</a>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="isLoadingRole"></loading>
    </section>
</template>

<script>
    import loading from '../../../Loading.vue';

    export default{
        components: {
            loading
        },
        data(){
            return {
                role: {},
                isLoadingRole: false,
            }
        },
        mounted() {
            console.info('Show component is mounted.');
            this.loadRole();
        },
        methods: {
            loadRole(){
                this.isLoadingRole = true;
                axios.get(showingRole.urls.show).then(response=> {
                    this.isLoadingRole = false;
                    if (response.data.status == true) {
                        this.role = response.data.role;
                    }
                }).catch(error=> {
                    this.isLoadingRole = false;
                })
            },
        },
        computed: {}
    }
</script>

<style>
</style>
