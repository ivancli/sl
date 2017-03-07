<template>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal">
                            <div class="form-group" v-for="appPref in appPrefs">
                                <label :for="appPref.id" v-text="appPref.element" class="control-label col-sm-4"></label>
                                <div class="col-sm-8">
                                    <input type="text" v-model="appPref.value" :id="appPref.id">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default{
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
            }
        }
    }
</script>

<style>

</style>