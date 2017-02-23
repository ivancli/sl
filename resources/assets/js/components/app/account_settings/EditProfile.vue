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
                <form onsubmit="return false;" class="form-horizontal sl-form-horizontal">
                    <div class="form-group">
                        <label for="title" class="control-label col-md-3">Title</label>
                        <div class="col-md-9">
                            <select class="form-control sl-form-control" id="title" v-model="title">
                                <option value="">Please select</option>
                                <option value="Miss">Miss</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="first_name" class="control-label col-md-3">First name</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="first_name" v-model="firstName">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="last_name" class="control-label col-md-3">Last name</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="last_name" v-model="lastName">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="email" class="control-label col-md-3">Email</label>
                        <div class="col-md-9">
                            <input class="form-control" disabled="disabled" type="email" id="email" v-model="email">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="industry" class="control-label col-md-3">Industry</label>
                        <div class="col-md-9">
                            <select class="form-control" id="industry" v-model="industry">
                                <option value="">Please select</option>
                                <option value="Accommodations">Accommodations</option>
                                <option value="Accounting">Accounting</option>
                                <option value="Advertising">Advertising</option>
                                <option value="Aerospace">Aerospace</option>
                                <option value="Agriculture &amp; Agribusiness">Agriculture &amp; Agribusiness</option>
                                <option value="Air Transportation">Air Transportation</option>
                                <option value="Apparel &amp; Accessories">Apparel &amp; Accessories</option>
                                <option value="Auto">Auto</option>
                                <option value="Banking">Banking</option>
                                <option value="Beauty &amp; Cosmetics">Beauty &amp; Cosmetics</option>
                                <option value="Biotechnology">Biotechnology</option>
                                <option value="Chemical">Chemical</option>
                                <option value="Communications">Communications</option>
                                <option value="Computer">Computer</option>
                                <option value="Construction">Construction</option>
                                <option value="Consulting">Consulting</option>
                                <option value="Consumer Electronics">Consumer Electrnics</option>
                                <option value="Education">Education</option>
                                <option value="Employment">Employment</option>
                                <option value="Energy">Energy</option>
                                <option value="Entertainment &amp; Recreation">Entertainment &amp; Recreation</option>
                                <option value="Fashion">Fashion</option>
                                <option value="Financial Services">Financial Services</option>
                                <option value="Fine Arts">Fine Arts</option>
                                <option value="Food &amp; Beverage">Food &amp; Beverage</option>
                                <option value="Health">Health</option>
                                <option value="Information">Information</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Insurance">Insurance</option>
                                <option value="Journalism &amp; News">Journalism &amp; News</option>
                                <option value="Legal Services">Legal Services</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Media &amp; Broadcasting">Media &amp; Broadcasting</option>
                                <option value="Medical Devices &amp; Supplies">Medical Devices &amp; Supplies</option>
                                <option value="Motion Pictures &amp; Video">Motion Pictures &amp; Video</option>
                                <option value="Music">Music</option>
                                <option value="Pharmaceutical">Pharmaceutical</option>
                                <option value="Public Administration">Public Administration</option>
                                <option value="Public Relations">Public Relations</option>
                                <option value="Publishing">Publishing</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Retail">Retail</option>
                                <option value="Service">Service</option>
                                <option value="Sports">Sports</option>
                                <option value="Technology">Technology</option>
                                <option value="Telecommunications">Telecommunications</option>
                                <option value="Tourism">Tourism</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Travel">Travel</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Video Game">Video Game</option>
                                <option value="Web Services">Web Services</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="company_type" class="control-label col-md-3">Company type</label>
                        <div class="col-md-9">
                            <select class="form-control" id="company_type" v-model="companyType">
                                <option value="">Please select</option>
                                <option value="Retailer">Retailer</option>
                                <option value="Brand">Brand</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_url" class="control-label col-md-3">My Site URL</label>
                        <div class="col-md-9">
                            <input class="form-control" placeholder="e.g. http://www.example.com" type="text" id="company_url" v-model="companyUrl">
                        </div>
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
    import loading from '../../Loading.vue';

    export default{
        components: {
            loading
        },
        data(){
            return {
                title: '',
                firstName: '',
                lastName: '',
                email: '',
                industry: '',
                companyType: '',
                companyUrl: '',
                isUpdatingProfile: false,
                errors: {},
                successMsg: ''
            }
        },
        mounted(){
            console.info('EditProfile component is mounted.');
            this.setInitUserInfo();
        },
        methods: {
            setInitUserInfo: function () {
                this.title = user.title == null ? '' : user.title;
                this.firstName = user.first_name;
                this.lastName = user.last_name;
                this.email = user.email;
                this.industry = user.metas.industry == null ? '' : user.metas.industry;
                this.companyType = user.metas.company_type == null ? '' : user.metas.company_type;
                this.companyUrl = user.metas.company_url == null ? '' : user.metas.company_url;
            },
            updateUserProfile: function () {
                this.clearErrors();
                this.clearSuccessMsg();
                this.isUpdatingProfile = true;
                axios.put(user.profileUrls.update, this.updateUserProfileData).then(response=> {
                    this.isUpdatingProfile = false;
                    if (response.data.status == true) {
                        this.setSuccessMsg();
                    }
                }).catch(error=> {
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
                this.successMsg = "You have successfully saved your profile"
            },
            clearSuccessMsg: function () {
                this.successMsg = '';
            }
        },
        computed: {
            updateUserProfileData(){
                return {
                    title: this.title,
                    first_name: this.firstName,
                    last_name: this.lastName,
                    industry: this.industry,
                    company_type: this.companyType,
                    company_url: this.companyUrl
                }
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