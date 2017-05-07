<template>
    <div class="add-item-block add-site-container">
        <div class="add-item-label add-site-label" v-show="!addingSite" @click.prevent="goingToAddSite">
            <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;ADD <span class="hidden-xs hidden-sm">THE</span> PRODUCT PAGE
            URL
        </div>
        <div class="add-item-controls" v-show="addingSite && !reachedSiteLimit">
            <div class="row">
                <div class="col-sm-12">
                    <form>
                        <input type="text" autocomplete="off" name="site_url"
                               class="txt-site-url form-control txt-item input-sm" v-model="newSiteURL"
                               ref="txt_new_site"
                               placeholder="Enter a product page URL">
                        <div class="buttons">
                            <button class="btn btn-primary btn-flat btn-sm" @click.prevent="addSite"
                                    :disabled="isAddingSite">
                                <span class="hidden-sm hidden-xs">
                                    CONFIRM
                                </span>
                                <span class="visible-sm visible-xs">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </button>
                            &nbsp;&nbsp;
                            <button class="btn btn-default btn-flat btn-cancel-add-site btn-sm"
                                    @click.prevent="cancelAddSite">
                                <span class="hidden-sm hidden-xs">
                                    CANCEL
                                </span>
                                <span class="visible-sm visible-xs">
                                    <i class="fa fa-times"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="add-item-controls" v-show="addingSite && reachedSiteLimit">
            You have reached the product URL limit of {{ subscriptionPlanName }} plan.<br/>
            Please <a href="#">upgrade your subscription</a> to add more URLs.
        </div>
        <error-modal :modal-errors="errors" @hideErrorModal="clearErrors"></error-modal>
        <loading v-if="isAddingSite"></loading>
    </div>
</template>

<script>
    import errorModal from '../../fragments/modals/Error.vue';
    import loading from '../../fragments/loading/Loading.vue';

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
            'product',
            'number-of-sites',
        ],
        components: {
            errorModal,
            loading
        },
        mounted(){
            console.info('AddSite component is mounted');
        },
        methods: {
            goingToAddSite() {
                this.addingSite = true;
                setTimeout(() => {
                    this.$refs['txt_new_site'].focus();
                }, 10)
            },
            cancelAddSite() {
                this.addingSite = false;
                this.clearNewSiteURL();
                this.$refs['txt_new_site'].blur();
            },
            addSite() {
                this.$refs['txt_new_site'].blur();
                this.isAddingSite = true;
                this.errors = {};
                axios.post('/site', this.addSiteData).then(response => {
                    this.isAddingSite = false;
                    if (response.data.status == true) {
                        this.addingSite = false;
                        this.clearNewSiteURL();
                        this.emitAddedSite(response.data.site);
                    }
                }).catch(error => {
                    this.isAddingSite = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors() {
                this.errors = {};
            },
            clearNewSiteURL() {
                this.newSiteURL = '';
            },
            emitAddedSite(site){
                this.$emit('added-site', site);
            },
        },
        computed: {
            addSiteData() {
                return {
                    full_path: this.newSiteURL,
                    product_id: this.product.id
                };
            },
            user(){
                return this.$store.getters.user
            },
            subscription(){
                if (this.user.hasOwnProperty('subscription')) {
                    return this.user.subscription
                } else {
                    return null;
                }
            },
            subscriptionPlan(){
                if (this.subscription != null) {
                    return this.subscription.subscriptionPlan
                } else {
                    return null;
                }
            },
            subscriptionPlanName(){
                if (this.subscriptionPlan != null) {
                    return this.subscriptionPlan.name;
                } else {
                    return null;
                }
            },
            maxNumberOfSites(){
                if (this.subscription != null) {
                    if (this.user.subscription.subscriptionCriteria.site == 0) {
                        return null;
                    }
                    return this.user.subscription.subscriptionCriteria.site;
                }
                return null;
            },
            reachedSiteLimit(){
                if (this.maxNumberOfSites != null && this.numberOfSites >= this.maxNumberOfSites) {
                    return true;
                } else {
                    return false;
                }
            },
        }
    }
</script>

<style>
    .add-site-container {
        border: 2px dashed lightgrey;
        border-radius: 5px;
        margin-top: 10px;
        margin-bottom: 10px;
        color: #777;
        font-weight: bold;
        cursor: pointer;
        width: 450px;
        max-width: 100%;
    }

    .add-site-container .add-item-label, .add-site-container .upgrade-for-add-item-controls {
        padding: 19px 19px 19px 50px;
    }

    .add-site-container i.label-icon {
        font-size: 25px;
        vertical-align: middle;
    }

    .add-site-container span.add-item-text {
        font-size: 14px;
        vertical-align: baseline;
    }

    .add-site-container .add-item-controls {
        background-color: #fff;
        padding: 13px;
    }

    .add-site-container .add-item-controls .buttons {
        position: absolute;
        right: 20px;
        top: 0;
    }

    .add-site-container .add-item-controls input {
        font-size: 18px;
        padding-right: 175px;
    }

    @media (max-width: 991px) {
        .add-site-container .add-item-controls input {
            padding-right: 100px;
        }
    }

    .add-site-container .txt-item {
        border: none;
    }

    @media (min-width: 768px) {
        .add-site-container .add-item-controls {
            padding-left: 15px;
        }
    }
</style>