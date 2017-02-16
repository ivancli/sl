<template>
    <div class="add-item-block add-site-container">
        <div class="add-item-label add-site-label" v-show="!addingSite" @click="goingToAddSite">
            <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;
            <div class="site-label-text-container">
                <div>ADD <span class="hidden-xs hidden-sm">THE</span> PRODUCT PAGE URL
                    <span class="hidden-xs hidden-sm">FOR THE PRICE YOU WANT TO TRACK.</span>
                </div>
                <div>
                    <span class="hidden-xs hidden-sm">E.G. http://www.company.com.au/productpage/price</span>
                </div>
            </div>
        </div>
        <div class="add-item-controls" v-show="addingSite">
            <div class="row">
                <div class="col-sm-12">
                    <input type="text" autocomplete="off" name="site_url" class="txt-site-url form-control txt-item"
                           v-model="newSiteURL" ref="txt_new_site"
                           placeholder="Enter a product page URL here">
                    <div class="buttons">
                        <button class="btn btn-primary btn-flat" @click="addSite">
                            <span class="hidden-sm hidden-xs">
                                ADD PRODUCT PAGE URL
                            </span>
                            <span class="visible-sm visible-xs">
                                <i class="fa fa-plus"></i>
                            </span>
                        </button>
                        &nbsp;&nbsp;
                        <button class="btn btn-default btn-flat btn-cancel-add-site" @click="cancelAddSite">
                            <span class="hidden-sm hidden-xs">
                                CANCEL
                            </span>
                            <span class="visible-sm visible-xs">
                                <i class="fa fa-times"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <error-modal :modal-errors="errors" @hideErrorModal="clearErrors"></error-modal>
    </div>
</template>

<script>
    import errorModal from '../../fragments/modals/Error.vue';

    export default {
        data() {
            return {
                addingSite: false, //determine visibility of panel
                newSiteURL: '', //site url input value
                isAddingSite: false, //promise of form submission,
                errors: {},
            }
        },
        props: [
            'product'
        ],
        components: {
            errorModal
        },
        mounted(){
            console.info('AddSite component is mounted');
        },
        methods: {
            goingToAddSite: function () {
                this.addingSite = true;
                setTimeout(()=> {
                    this.$refs['txt_new_site'].focus();
                }, 10)
            },
            cancelAddSite: function () {
                this.addingSite = false;
                this.clearNewSiteURL();
                this.$refs['txt_new_site'].blur();
            },
            addSite: function () {
                this.isAddingSite = true;
                this.errors = {};
                axios.post('/site', this.addSiteData).then(response=> {
                    this.isAddingSite = false;
                    if (response.data.status == true) {
                        this.addingSite = false;
                    }
                    this.clearNewSiteURL();
                    this.$emit('addedSite');
                }).catch(error=> {
                    this.isAddingSite = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            },
            clearNewSiteURL: function () {
                this.newSiteURL = '';
            }
        },
        computed: {
            addSiteData: function () {
                return {
                    full_path: this.newSiteURL,
                    product_id: this.product.id
                };
            }
        }
    }
</script>

<style>
    .add-item-block.add-site-container .add-item-label,
    .add-item-block.add-site-container .upgrade-for-add-item-controls {
        padding-left: 25px;
    }

    .add-item-label.add-site-label {
        padding-top: 9px;
        padding-bottom: 9px;
    }

    @media (max-width: 991px) {
        .add-item-label.add-site-label {
            padding-top: 18px;
            padding-bottom: 18px;
        }
    }

    .site-label-text-container {
        display: inline-block;
        vertical-align: middle;
    }
</style>