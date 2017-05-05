<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <p class="form-control-static text-success" v-if="successMsg.length > 0"
                                       v-text="successMsg"></p>
                                </div>
                            </div>
                            <single-edit-app-pref v-for="(value, element) in appPrefs" :value="value" :element="element"
                                                  @updating-pref="updatingPref"></single-edit-app-pref>
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-primary btn-sm btn-flat" @click.prevent="updatePref">UPDATE
                                </button>
                                <a href="/app-preference" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="isLoadingAppPreference || isUpdatingAppPreference"></loading>
    </section>
</template>

<script>
    import singleEditAppPref from './SingleEditAppPref.vue';
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        components: {
            singleEditAppPref,
            loading
        },
        data(){
            return {
                isLoadingAppPreference: false,
                isUpdatingAppPreference: false,
                successMsg: "",
                appPrefs: []
            }
        },
        mounted(){
            console.info('Index component is mounted.');
            this.loadAppPrefs();
        },
        methods: {
            loadAppPrefs(){
                this.isLoadingAppPreference = true
                axios.get('/app-preference').then(response => {
                    this.isLoadingAppPreference = false;
                    if (response.data.status == true) {
                        this.appPrefs = response.data.appPrefs;
                    }
                }).catch(error => {
                    this.isLoadingAppPreference = false;
                })
            },
            updatingPref(pref){
                this.appPrefs[pref.element] = pref.value;
            },
            updatePref(){
                this.isUpdatingAppPreference = true;
                axios.post('/app-preference', this.updateAppPrefData).then(response => {
                    this.isUpdatingAppPreference = false;
                    if (response.data.status == true) {
                        this.successMsg = "Application preferences have been updated."
                    }
                }).catch(error => {
                    this.isUpdatingAppPreference = false;
                })
            }
        },
        computed: {
            updateAppPrefData(){
                let data = [];
                for (let index in this.appPrefs) {
                    if (this.appPrefs.hasOwnProperty(index)) {
                        data.push({
                            element: index,
                            value: this.appPrefs[index]
                        });
                    }
                }
                return {
                    appPrefs: data
                };
            }
        }
    }
</script>

<style>

</style>