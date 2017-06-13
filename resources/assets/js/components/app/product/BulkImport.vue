<template>
    <div style="position: relative">
        <div class="bulk-import-loading" v-if="productBulkJobs.length > 0 || urlBulkJobs.length > 0">
            <div class="backdrop"></div>
            <div class="loading-core"></div>
            <div class="loading-text">
                <p>We're importing the data into your account and this may take a while. We'll let you know once it's done.</p>
                <p>Meanwhile, you can continue using SpotLite</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-offset-3 col-lg-6 col-md-offset-1 col-md-10">
                <div class="p-10">
                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <p class="text-muted">
                                In order to Bulk Import data into SpotLite, please download the
                                <a href="/csvs/import_categories_products_template.csv" download>Bulk Import Categories And Products Template</a>
                                and
                                <a href="/csvs/import_urls_template.csv" download>Bulk Import Product Page URLs Template</a>
                                .
                            </p>
                        </div>
                    </div>
                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <p>
                                <strong>Instructions:</strong>
                            </p>
                            <p class="text-muted">
                                If you wish to import Categories, Products and Product Page URLs, please go to <span class="text-tiffany">STEP 1</span>.
                            </p>
                            <p class="text-muted">
                                If you wish to import Product Page URLs only, please go to <span class="text-tiffany">STEP 2</span>.
                            </p>
                        </div>
                    </div>
                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <p>
                                <strong class="text-danger">Important:</strong>
                            </p>
                            <p class="text-muted">
                                The following actions might cause errors:
                            </p>
                            <ul class="text-muted">
                                <li>Do not remove any columns (all Categories and Products are mandatory)</li>
                                <li>Do not leave Category or Product blank (each product must belong to a Category)</li>
                                <li>Errors or misspellings on Category or Product names will result in the creation of new Category or Product</li>
                                <li>There are 2 templates - first one for Categories and Products and second one for Product Page URLs. Make sure you save each template as a CSV file before uploading it on step 1 and 2 respectively.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <h4 class="text-tiffany">STEP 1 - IMPORT CATEGORIES AND PRODUCTS</h4>
                            <p class="text-muted">Choose the Bulk Import CSV file you wish to upload into SpotLite</p>
                        </div>
                    </div>

                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(step_one_errors).length > 0">
                                <li v-for="error in step_one_errors">
                                    <div v-if="error.constructor != Array" v-text="error"></div>
                                    <div v-else v-for="message in error" v-text="message"></div>
                                </li>
                            </ul>
                            <ul class="p-b-10 p-l-20 success-container" v-if="lastBulkJob != null && lastBulkJob.type == 'product'">
                                <li class="text-tiffany">
                                    Your previous request "{{ lastBulkJob.file_name }}" has been imported successfully.
                                </li>
                            </ul>
                            <form class="form-horizontal form-sl-horizontal">
                                <div class="form-group">
                                    <label for="category-product-file" class="col-sm-3 control-label">Select CSV File</label>
                                    <div class="col-sm-9">
                                        <input type="file" id="category-product-file" class="form-control" @change.prevent="updateStepOneFile">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button class="btn btn-primary btn-flat" @click.prevent="storeImportCategoriesAndProducts">CONFIRM</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="text-tiffany">STEP 2 - IMPORT PRODUCT PAGE URLs</h4>
                            <p class="text-muted">Since you already have Categories and Products set up, you may not wish to have new Categories or Products created or existing Product meta Data replaced by your Bulk Import data. If that's the case, please choose one or more options from the following:</p>
                        </div>
                    </div>

                    <div class="row m-b-20">
                        <div class="col-sm-12">
                            <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(step_two_errors).length > 0">
                                <li v-for="error in step_two_errors">
                                    <div v-if="error.constructor != Array" v-text="error"></div>
                                    <div v-else v-for="message in error" v-text="message"></div>
                                </li>
                            </ul>
                            <ul class="p-b-10 p-l-20 success-container" v-if="lastBulkJob != null && lastBulkJob.type == 'url'">
                                <li class="text-tiffany">
                                    Your previous request "{{ lastBulkJob.file_name }}" has been imported successfully.
                                </li>
                            </ul>
                            <form class="form-horizontal form-sl-horizontal">
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <div class="checkbox">
                                            <label for="chk-no-new-categories">
                                                <input type="checkbox" id="chk-no-new-categories" v-model="noNewCategories">
                                                Do not create new categories
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <div class="checkbox">
                                            <label for="chk-no-new-products">
                                                <input type="checkbox" id="chk-no-new-products" v-model="noNewProducts">
                                                Do not create new products
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url-file" class="col-sm-3 control-label">Select CSV File</label>
                                    <div class="col-sm-9">
                                        <input type="file" id="url-file" class="form-control" @change.prevent="updateStepTwoFile">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary btn-flat" @click.prevent="storeImportUrls">CONFIRM</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <loading v-if="(isLoadingJobs || isImporting) && !(productBulkJobs.length > 0 || urlBulkJobs.length > 0)"></loading>
        </div>
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
                bulkJobs: [],
                lastBulkJob: null,
                isLoadingJobs: false,
                isImporting: false,
                step_one_errors: {},
                step_two_errors: {},
                step_one_file: null,
                step_two_file: null,
                noNewCategories: false,
                noNewProducts: false,
                bulkJobsCheckerPromise: null,
            };
        },
        mounted(){
            console.info('BulkImport component mounted.');
            this.loadBulkImportJobs();
            this.loadLastBulkImportJob();
        },
        watch: {
            bulkJobs(bulkJobs){
                if (bulkJobs.length > 0) {
                    this.bulkJobsCheckerPromise = setTimeout(() => {
                        this.loadBulkImportJobs();
                    }, 5000);
                } else {
                    this.loadLastBulkImportJob();
                }
            }
        },
        methods: {
            loadBulkImportJobs(){
                this.isLoadingJobs = true;
                axios.get('/bulk-import').then(response => {
                    this.isLoadingJobs = false;
                    if (response.data.status === true) {
                        this.bulkJobs = response.data.bulkJobs;
                    }
                }).catch(error => {
                    this.isLoadingJobs = false;
                    console.info("unable to load bulk jobs");
                })
            },
            loadLastBulkImportJob(){
                this.isLoadingJobs = true;
                axios.get('/bulk-import', {params: {last: true}}).then(response => {
                    this.isLoadingJobs = false;
                    if (response.data.status === true) {
                        this.lastBulkJob = response.data.bulkJobs;
                    }
                }).catch(error => {
                    this.isLoadingJobs = false;
                    console.info("unable to load bulk jobs");
                })
            },
            storeImportCategoriesAndProducts()
            {
                this.isImporting = true;
                this.lastBulkJob = null;
                axios.post('/bulk-import', this.storeImportCategoriesAndProductsRequestData, {
                    'content-type': 'multipart/form-data',
                }).then(response => {
                    this.isImporting = false;
                    if (response.data.status === true) {
                        this.loadBulkImportJobs();
                    }
                }).catch(error => {
                    this.isImporting = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.step_one_errors = error.response.data;
                    }
                })
            },
            storeImportUrls(){
                this.isImporting = true;
                this.lastBulkJob = null;
                axios.post('/bulk-import', this.storeImportUrlsRequestData).then(response => {
                    this.isImporting = false;
                    if (response.data.status === true) {
                        this.loadBulkImportJobs();
                    }
                }).catch(error => {
                    this.isImporting = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.step_two_errors = error.response.data;
                    }
                })
            },
            updateStepOneFile(){
                this.step_one_errors = {};
                if (document.getElementById('category-product-file').files.length > 0) {
                    this.step_one_file = document.getElementById('category-product-file').files[0];
                } else {
                    this.step_one_file = null;
                }
            },
            updateStepTwoFile(){
                this.step_two_errors = {};
                if (document.getElementById('url-file').files.length > 0) {
                    this.step_two_file = document.getElementById('url-file').files[0];
                } else {
                    this.step_two_file = null;
                }
            }
        },
        computed: {
            storeImportCategoriesAndProductsRequestData(){
                let data = new FormData();
                data.append('type', 'product');
                data.append('file', this.step_one_file)
                return data;
            },
            storeImportUrlsRequestData(){
                let data = new FormData();
                data.append('type', 'url');
                data.append('file', this.step_two_file);
                data.append('no_new_categories', this.noNewCategories);
                data.append('no_new_products', this.noNewProducts);
                return data;
            },
            productBulkJobs(){
                return this.bulkJobs.filter(function (job) {
                    return job.type === 'product';
                });
            },
            urlBulkJobs(){
                return this.bulkJobs.filter(function (job) {
                    return job.type === 'url';
                });
            }
        }
    }
</script>

<style>
    .bulk-import-loading {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }

    .bulk-import-loading .backdrop {
        background-color: black;
        opacity: 0.5;
        position: absolute;
        width: 100%;
        height: 100%;
    }

    .bulk-import-loading .loading-core {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -50px;
        margin-top: -50px;
        width: 100px;
        height: 100px;
        background-image: url('/images/spinner.png');
        background-size: 100px 3600px;
        -webkit-animation: loading-v 1s steps(36) infinite;
        z-index: 2000;
    }

    .bulk-import-loading .loading-text {
        z-index: 2000;
        position: absolute;
        top: 50%;
        text-align: center;
        margin-top: 50px;
        color: #fff;
        font-size: 20px;
        font-weight: bold;
        width: 100%;
    }

    @keyframes loading-v {
        0% {
            background-position-y: 0;
        }
        100% {
            background-position-y: -3600px;
        }
    }
</style>