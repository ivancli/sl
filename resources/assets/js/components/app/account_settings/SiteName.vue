<template>
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-1 col-md-10">
            <div class="row">
                <div class="col-sm-12">
                    <p>Adding a Site Name to the list below will replace the corresponding domain name in the Products screen as well as all charts, reports and alerts.</p>
                    <p>For example, you can use 'SpotLite Australia' instead of 'www.spotlite.com.au'.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-3">
                    <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                        <li v-for="error in errors">
                            <div v-if="error.constructor != Array" v-text="error"></div>
                            <div v-else v-for="message in error" v-text="message"></div>
                        </li>
                    </ul>
                    <ul class="p-b-10 p-l-20 success-container" v-if="successMsg != ''">
                        <li v-text="successMsg"></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="p-10">
                        <form class="form-horizontal form-sl-horizontal">
                            <div class="form-group" v-for="userDomain in userDomains">
                                <label class="control-label col-md-3">{{ userDomain.domain }}</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" v-model="userDomain.alias">
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary btn-flat" @click.prevent="updateUserDomains">UPDATE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="isLoadingUserDomains || isUpdatingUserDomains"></loading>
    </div>
</template>

<script>
    import loading from '../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading,
        },
        data(){
            return {
                userDomains: {},
                isLoadingUserDomains: true,
                isUpdatingUserDomains: false,
                errors: {},
                successMsg: '',
            }
        },
        mounted(){
            console.info('SiteName component mounted.');
            this.loadUserDomains();
        },
        methods: {
            loadUserDomains(){
                this.isLoadingUserDomains = true;
                axios.get('user/user-domain').then(response => {
                    this.isLoadingUserDomains = false;
                    if (response.data.status === true) {
                        this.setUserDomains(response.data.userDomains);
                    }
                }).catch(error => {
                    this.isLoadingUserDomains = false;
                    console.info('error', error);
                });
            },
            setUserDomains(userDomains){
                this.userDomains = userDomains;
            },
            updateUserDomains(){
                this.isUpdatingUserDomains = true;
                this.clearSuccessMsg();
                axios.post('user/user-domain', this.updateUserDomainsRequestData).then(response => {
                    this.isUpdatingUserDomains = false;
                    if (response.data.status === true) {
                        this.setSuccessMsg();
                    }
                }).catch(error => {
                    this.isUpdatingUserDomains = false;
                    console.info('error', error);
                })
            },
            setSuccessMsg(){
                this.successMsg = 'Site names settings have been updated.';
            },
            clearSuccessMsg(){
                this.successMsg = '';
            }
        },
        computed: {
            updateUserDomainsRequestData(){
                return this.userDomains;
            }
        }
    }
</script>

<style>

</style>