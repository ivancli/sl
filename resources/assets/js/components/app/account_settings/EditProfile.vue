<template>

    <div class="row">
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-1 col-md-10">
            <div class="p-10">
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
                <form onsubmit="return false;" class="form-horizontal form-sl-horizontal">
                    <div>
                        <div class="form-group">
                            <h4 class="f-w-bold">My Details</h4>
                        </div>
                        <profile-form></profile-form>
                        <hr>
                    </div>
                    <div>
                        <div class="form-group">
                            <h4 class="f-w-bold">Company's Details</h4>
                        </div>
                        <company-form></company-form>
                        <hr>
                    </div>
                    <div>
                        <div class="form-group">
                            <h4 class="f-w-bold">Display Preferences</h4>
                        </div>
                        <display-form></display-form>
                        <hr>
                    </div>
                    <div>
                        <div class="form-group">
                            <h4 class="f-w-bold">SpotLite Digest</h4>
                        </div>

                        TBC
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary btn-flat" @click.prevent="updateUserProfile">UPDATE</button>
                    </div>
                </form>

            </div>
        </div>
        <loading v-if="isUpdatingProfile"></loading>
    </div>
</template>

<script>
    import profileForm from './forms/Profile.vue';
    import companyForm from './forms/Company.vue';
    import displayForm from './forms/Display.vue';

    import loading from '../../fragments/loading/Loading.vue';

    export default{
        components: {
            profileForm,
            companyForm,
            displayForm,
            loading
        },
        data(){
            return {
                isUpdatingProfile: false,
                errors: {},
                successMsg: ''
            }
        },
        mounted(){
            console.info('EditProfile component is mounted.');
        },
        methods: {
            updateUserProfile: function () {
                this.clearErrors();
                this.clearSuccessMsg();
                this.isUpdatingProfile = true;
                axios.put(user.profileUrls.update, this.updateUserProfileData).then(response => {
                    this.isUpdatingProfile = false;
                    if (response.data.status == true) {
                        this.setSuccessMsg();
                    }
                }).catch(error => {
                    this.isUpdatingProfile = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            },
            setSuccessMsg: function () {
                this.successMsg = "Your profile has been updated."
            },
            clearSuccessMsg: function () {
                this.successMsg = '';
            }
        },
        computed: {
            updateUserProfileData(){
                return {
                    profile: this.profile,
                    company: this.company,
                    display: this.display,
                }
            },
            profile(){
                return this.$store.getters.profile;
            },
            company(){
                return this.$store.getters.company;
            },
            display(){
                return this.$store.getters.display;
            }
        }
    }
</script>

<style>
    .required label::after {
        content: " *";
        color: #ff0000;
    }

    .success-container {
        color: #439c8b;
    }
</style>