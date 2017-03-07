<template>
    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal">
                            <single-edit-app-pref v-for="(value, element) in appPrefs" :value="value" :element="element"
                                                  @updating-pref="updatingPref"></single-edit-app-pref>
                            <button class="btn btn-primary btn-sm btn-flat" @click.prevent="updatePref">UPDATE</button>
                            <a href="/app-preference" class="btn btn-default btn-sm btn-flat">CANCEL</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import singleEditAppPref from './SingleEditAppPref.vue';

    export default{
        components: {
            singleEditAppPref
        },
        data(){
            return {
                isLoadingAppPreference: false,
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
                console.info('pref.element', pref.element);
                console.info('pref.value', pref.value);
                this.appPrefs[pref.element] = pref.value;
            },
            updatePref(){
                /*TODO submit form*/
            }
        },
        computed: {
            updateAppPrefData(){
                let data = [];
                this.appPrefs.forEach((item, index) => {
                    data.push({
                        element: index,
                        value: item
                    });
                });
                return data;
            }
        }
    }
</script>

<style>

</style>