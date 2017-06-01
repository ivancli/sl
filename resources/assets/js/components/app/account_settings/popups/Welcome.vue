<template>
    <div class="modal welcome-modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 text-center m-b-10">
                                <img src="/images/logo-fixed-2.png" width="250">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center m-b-10">
                                <h2>Welcome to SpotLite!</h2>
                                <p>We want you to get the most out of your trial so, to kick off, we want to personalise your experience.</p>
                                <p>Simply answer the questions below and follow the journey!</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="jumbotron set-welcome-container">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-6 col-xs-offset-2 col-xs-8 text-center">
                                            <p class="welcome-form-heading">Please choose:</p>

                                            <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                                                <li v-for="error in errors">
                                                    <div class="text-left" v-if="error.constructor != Array" v-text="error"></div>
                                                    <div class="text-left" v-else v-for="message in error" v-text="message"></div>
                                                </li>
                                            </ul>
                                            <form class="form-horizontal" @submit.prevent="onClickGoToProduct">
                                                <div class="form-group required">
                                                    <select class="form-control" v-model="industry">
                                                        <option value="">What is your industry?</option>
                                                        <option>Accommodations</option>
                                                        <option>Accounting</option>
                                                        <option>Advertising</option>
                                                        <option>Aerospace</option>
                                                        <option>Agriculture &amp; Agribusiness</option>
                                                        <option>Air Transportation</option>
                                                        <option>Apparel &amp; Accessories</option>
                                                        <option>Auto</option>
                                                        <option>Banking</option>
                                                        <option>Beauty &amp; Cosmetics</option>
                                                        <option>Biotechnology</option>
                                                        <option>Chemical</option>
                                                        <option>Communications</option>
                                                        <option>Computer</option>
                                                        <option>Construction</option>
                                                        <option>Consulting</option>
                                                        <option>Consumer Electronics</option>
                                                        <option>Education</option>
                                                        <option>Employment</option>
                                                        <option>Energy</option>
                                                        <option>Entertainment &amp; Recreation</option>
                                                        <option>Fashion</option>
                                                        <option>Financial Services</option>
                                                        <option>Fine Arts</option>
                                                        <option>Food &amp; Beverage</option>
                                                        <option>Health</option>
                                                        <option>Information</option>
                                                        <option>Information Technology</option>
                                                        <option>Insurance</option>
                                                        <option>Journalism &amp; News</option>
                                                        <option>Legal Services</option>
                                                        <option>Manufacturing</option>
                                                        <option>Media &amp; Broadcasting</option>
                                                        <option>Medical Devices &amp; Supplies</option>
                                                        <option>Motion Pictures &amp; Video</option>
                                                        <option>Music</option>
                                                        <option>Pharmaceutical</option>
                                                        <option>Public Administration</option>
                                                        <option>Public Relations</option>
                                                        <option>Publishing</option>
                                                        <option>Real Estate</option>
                                                        <option>Retail</option>
                                                        <option>Service</option>
                                                        <option>Sports</option>
                                                        <option>Technology</option>
                                                        <option>Telecommunications</option>
                                                        <option>Tourism</option>
                                                        <option>Transportation</option>
                                                        <option>Travel</option>
                                                        <option>Utilities</option>
                                                        <option>Video Game</option>
                                                        <option>Web Services</option>
                                                    </select>
                                                </div>
                                                <div class="form-group required">
                                                    <select class="form-control" v-model="companyType">
                                                        <option value="">What is your company type?</option>
                                                        <option>Retailer</option>
                                                        <option>Brand</option>
                                                        <option>Other</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="What is your site URL? e.g. http://www.company.com" v-model="companyUrl">
                                                </div>
                                                <div class="form-group text-left">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" v-model="showSampleData">
                                                            I would like to start with sample data
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group" v-if="showSampleData">
                                                    <select class="form-control" multiple="multiple" v-model="selectedCategories">
                                                        <option :value="sampleCategory.id" v-for="sampleCategory in sampleCategories">
                                                            {{ sampleCategory.category_name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-b-40">
                            <div class="col-sm-12">
                                <ul class="steps">
                                    <li data-step="1" class="active">
                                        <span class="step">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        <div class="title">
                                            <div>
                                                STEP 1
                                            </div>
                                            <div class="description">
                                                Let's kick off by creating your Dashboard
                                            </div>
                                        </div>
                                    </li>
                                    <li data-step="2">
                                        <span class="step">&nbsp;</span>
                                        <div class="title">
                                            <div>
                                                STEP 2
                                            </div>
                                            <div class="description">
                                                Set up product prices you want to track
                                            </div>
                                        </div>
                                    </li>
                                    <li data-step="3">
                                        <span class="step">&nbsp;</span>
                                        <div class="title">
                                            <div>
                                                STEP 3
                                            </div>
                                            <div class="description">
                                                Set notifications
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary btn-flat" @click.prevent="onClickGoToProduct" :disabled="isSettingWelcome">GO TO PRODUCTS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <loading v-if="isSettingWelcome || isLoadingSampleAccount"></loading>
    </div>
</template>

<script>
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading,
        },
        data(){
            return {
                industry: "",
                companyType: "",
                showSampleData: false,
                selectedCategories: [],
                isSettingWelcome: false,
                isLoadingSampleAccount: false,
                sampleCategories: [],
                errors: {},
            }
        },
        mounted(){
            console.info('Welcome component mounted.');
            this.loadSampleAccountCategories();
        },
        methods: {
            loadSampleAccountCategories(){
                this.isLoadingSampleAccount = true;
                axios.get('/user/sample-account').then(response => {
                    this.isLoadingSampleAccount = false;
                    if (response.data.status === true) {
                        this.sampleCategories = response.data.categories;
                    }
                }).catch(error => {
                    this.isLoadingSampleAccount = false;
                    console.info('error', error);
                });
            },
            onClickGoToProduct(){
                this.isSettingWelcome = true;

                axios.post('/user/sample-account', this.saveWelcomeRequestData).then(response => {
                    this.isSettingWelcome = false;
                    if (response.data.status === true) {
                        this.emitSetWelcome();
                    }
                }).catch(error => {
                    this.isSettingWelcome = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                });
            },
            emitSetWelcome(){
                this.$emit('set-welcome');
            }
        },
        computed: {
            saveWelcomeRequestData(){
                return {
                    industry: this.industry,
                    company_type: this.companyType,
                    company_url: this.companyUrl,
                    selected_category_ids: this.selectedCategories,
                }
            }
        }
    }
</script>

<style>
    .set-welcome-container .form-group {
        position: relative;
    }

    .set-welcome-container .form-group.required::before {
        content: "*";
        color: red;
        font-weight: bold;
        position: absolute;
        left: -10px;
        top: 10px;
    }

    .welcome-form-heading {
        font-size: 15px;
        text-align: center;
        margin-bottom: 30px;
    }

    @media screen and (min-width: 601px) {
        .set-welcome-container .form-group {
            margin-bottom: 25px;
        }
    }

    @media screen and (min-width: 901px) {
        .welcome-modal .modal-content,
        .welcome-modal .modal-card {
            width: 900px;
        }
    }

    div.set-welcome-container {
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .steps {
        list-style: none;
        display: table;
        width: 100%;
        padding: 0;
        margin: 0;
        position: relative;
    }

    .steps > li {
        display: table-cell;
        text-align: center;
        width: 1%;
    }

    .steps > li .step {
        border: 5px solid #CED1D6;
        /*color: #546474;*/
        color: #ffffff;
        font-size: 15px;
        border-radius: 100%;
        position: relative;
        z-index: 2;
        display: inline-block;
        width: 40px;
        height: 40px;
    }

    .steps > li .step, .steps > li.complete .step:before {
        line-height: 30px;
        text-align: center;
    }

    .steps > li.active .step, .steps > li.complete .step:before {
        background-color: #5293C4;
    }

    .steps > li .step, .steps > li.complete .step:before {
        background-color: #ced1d6;
    }

    .steps > li.active .step, .steps > li.active:before, .steps > li.complete .step, .steps > li.complete:before {
        border-color: #5293C4;
    }

    .steps > li .title {
        display: block;
        margin-top: 4px;
        max-width: 100%;
        color: #949EA7;
        font-size: 14px;
        z-index: 104;
        text-align: center;
        table-layout: fixed;
        word-wrap: break-word;
    }

    .steps > li.active .title, .steps > li.complete .title {
        color: #2B3D53;
        font-weight: bold;
    }

    .steps > li.active .step, .steps > li.active:before, .steps > li.complete .step, .steps > li.complete:before {
        border-color: #5293C4;
    }

    .steps > li:first-child:before {
        max-width: 51%;
        left: 50%;
    }

    .steps > li:before {
        display: block;
        content: "";
        width: 100%;
        height: 1px;
        font-size: 0;
        overflow: hidden;
        border-top: 4px solid #CED1D6;
        position: relative;
        top: 21px;
        z-index: 1;
    }

    .steps > li:last-child:before {
        max-width: 50%;
        width: 50%;
    }
</style>