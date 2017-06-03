<template>
    <tbody>
    <tr class="site-wrapper">
        <td class="site-url vertical-align-middle" :class="mySiteClass">
            <a :href="site.siteUrl" target="_blank" class="text-muted site-url-link" :title="site.siteUrl"
               v-show="!editingSiteURL">
                {{ displayName }}
            </a>

            <edit-site :editing-site="site" :going-to-edit-site="editingSiteURL" @edited-site="editedSite" @cancel-edit-site-url="cancelEditSiteURL"></edit-site>
        </td>
        <td class="vertical-align-middle col-recent-price" :class="mySiteClass">
            <div class="text-right">
                <div v-if="item != null && item.recentPrice != null">
                    ${{ item.recentPrice | currency }}
                </div>
                <div v-else>
                    <div class="p-r-30">
                        <strong><i class="fa fa-minus"></i></strong>
                    </div>
                </div>
            </div>
        </td>
        <td class="vertical-align-middle" :class="mySiteClass">
            <div class="text-center">
                <div v-if="item != null && item.availability != null">
                    {{ item.availability == true ? 'Yes' : 'No' }}
                </div>
                <div v-else>
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm col-previous-price" :class="mySiteClass">
            <div class="text-right">
                <div v-if="item != null && item.previousPrice != null">
                    ${{ item.previousPrice | currency }}
                </div>
                <div v-else>
                    <div class="p-r-30">
                        <strong><i class="fa fa-minus"></i></strong>
                    </div>
                </div>
            </div>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm col-price-change" :class="mySiteClass">
            <div class="text-right">
                <div v-if="item != null && item.priceChange != null">
                    ${{ item.priceChange | currency }}
                </div>
                <div v-else>
                    <div class="p-r-10">
                        <strong><i class="fa fa-minus"></i></strong>
                    </div>
                </div>
            </div>
        </td>
        <td class="hidden-xs vertical-align-middle hidden-xs hidden-sm p-l-20" :class="mySiteClass">
            <div v-if="item != null && item.lastChangedAt != null">
                {{ item.lastChangedAt | formatDateTime(dateFormat) }}
            </div>
            <div v-else>
                <div class="p-l-30">
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="text-right action-cell vertical-align-middle">
            <a href="#" class="btn-action hidden-md hidden-lg" title="show details" @click.prevent="toggleSiteDetails" v-if="subscriptionIsValid">
                <i class="fa fa-info-circle"></i>
            </a>
            <a href="#" class="btn-action" title="edit" v-if="!editingSiteURL && subscriptionIsValid" @click.prevent="goingToEditSiteURL">
                <i class="fa fa-pencil"></i>
            </a>
            <a href="#" class="btn-action" title="choose item" v-if="site.url.itemsCount > 1 && subscriptionIsValid" @click.prevent="onClickSelectItem">
                <i class="fa fa-exclamation-triangle" v-if="item == null"></i>
                <i class="fa fa-list-ul" v-else></i>
            </a>
            <a href="#" class="btn-action" v-if="item != null" title="chart" @click.prevent="onClickViewChart">
                <i class="fa fa-line-chart"></i>
            </a>
            <a href="#" class="btn-action" title="delete" @click.prevent="onClickDeleteSite">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
    </tr>
    <tr class="hidden-lg hidden-md" v-show="showSiteDetails">
        <td colspan="9">
            <table class="table">
                <tbody>
                <tr>
                    <th>Site</th>
                    <td>{{site.siteUrl | domain}}</td>
                </tr>
                <tr>
                    <th>URL:</th>
                    <td class="url-container">
                        <a :href="site.siteUrl" v-text="site.siteUrl"></a>
                    </td>
                </tr>
                <tr>
                    <th>Is my site:</th>
                    <td>Yes</td>
                </tr>
                <tr>
                    <th>Current price:</th>
                    <td>
                        <div v-if="item != null && item.recentPrice != null">
                            ${{ item.recentPrice | currency }}
                        </div>
                        <div v-else>
                            <div class="p-l-30">
                                <strong><i class="fa fa-minus"></i></strong>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <select-item-popup v-if="isSelectingItem || isNewlyCreated" :editing-site="site" @selected-item="selectedItem" @hide-modal="cancelSelectingItem"></select-item-popup>
    <site-chart v-if="isViewingChart" :charting-site="site" @hide-modal="cancelViewingChart"></site-chart>
    <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
    </tbody>
</template>

<script>
    import editSite from './EditSite.vue';
    import selectItemPopup from './SelectItemPopup.vue';
    import siteChart from './chart/SiteChart.vue';
    import deleteConfirmation from '../../fragments/modals/DeleteConfirmation.vue';

    import formatDateTime from '../../../filters/formatDateTime';
    import currency from '../../../filters/currency';
    import domain from '../../../filters/domain';

    export default {
        components: {
            editSite,
            selectItemPopup,
            siteChart,
            deleteConfirmation,
        },
        props: [
            'current-site',
            'is-newly-created',
        ],
        data() {
            return {
                editingSiteURL: false,
                showSiteDetails: false,
                deleteParams: {
                    title: 'URL',
                    list: [
                        'All pricing information related to this Site, including any information displayed on your Charts and Dashboards',
                        'All Product Reports generated',
                        'All alerts set up for this Site',
                        'This Site\'s pricing information tracked to date',
                    ],
                    active: false
                },
                isDeletingSite: false,
                isSelectingItem: false,
                isViewingChart: false,
            }
        },
        mounted() {
            console.info('SingleSite component is mounted');
        },
        methods: {
            toggleSiteDetails() {
                this.showSiteDetails = !this.showSiteDetails;
            },

            /*region edit site*/
            goingToEditSiteURL() {
                this.editingSiteURL = true;
            },
            editedSite() {
                this.editingSiteURL = false;
                this.emitReloadSite();
            },
            cancelEditSiteURL() {
                this.editingSiteURL = false;
            },
            /*endregion*/
            /*region view site chart*/
            onClickViewChart(){
                this.isViewingChart = true;
            },
            cancelViewingChart(){
                this.isViewingChart = false;
            },
            /*endregion*/
            /*region delete site*/
            onClickDeleteSite(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteSite(() => {
                    this.emitDeleteSite();
                })
            },
            deleteSite(callback){
                this.isDeletingSite = true;
                axios.delete(this.site.urls.delete).then(response => {
                    this.isDeletingSite = false;
                    if (response.data.status === true) {
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                }).catch(error => {
                    this.isDeletingSite = false;
                })
            },
            /*endregion*/
            /*region select item*/
            onClickSelectItem(){
                this.isSelectingItem = true;
            },
            selectedItem(item){
                this.emitReloadSite();
                this.emitSelectedItem(item);
                this.cancelSelectingItem();
            },
            cancelSelectingItem(){
                this.emitSelectedItem();
                this.isSelectingItem = false;
            },
            emitSelectedItem(item){
                if (typeof item !== 'undefined') {
                    this.$emit('selected-item', item);
                } else {
                    this.$emit('selected-item');
                }
            },
            /*endregion*/
            /*region emit events*/
            emitReloadSite(){
                this.$emit('reload-site', this.site);
            },
            emitReloadSites() {
                this.$emit('reload-sites');
            },
            emitDeleteSite(){
                this.$emit('deleted-site', this.site);
            }
            /*endregion*/
        },
        computed: {
            site(){
                return this.currentSite;
            },
            item(){
                return this.site.item;
            },
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
            },
            user(){
                return this.$store.getters.user;
            },
            subscription(){
                if (typeof this.user.subscription !== 'undefined' && this.user.subscription !== null) {
                    return this.user.subscription;
                }
                return null;
            },
            subscriptionIsValid(){
                if (this.subscription !== null) {
                    return this.subscription.isValid;
                }
                return true;
            },
            userDomains(){
                return this.$store.getters.userDomains;
            },
            sellerUsername(){
                if (this.item !== null) {
                    return this.item.sellerUsername;
                }
                return null;
            },
            displayName(){
                /*TODO need to add ebay site store name before the following validations*/
                if (this.sellerUsername !== null) {
                    return "eBay: " + this.sellerUsername;
                }
                let siteDomain = this.$options.filters.domain(this.site.siteUrl);

                let userDomains = this.userDomains.filter(userDomain => {
                    let domain = this.$options.filters.domain(userDomain.domain);
                    return siteDomain.indexOf(domain) > -1;
                });
                if (userDomains.length > 0) {
                    let userDomain = userDomains[0];
                    if (userDomain.alias !== null && userDomain.alias.length > 0) {
                        return userDomain.alias;
                    }
                }

                return siteDomain;
            },
            companyUrl(){
                return this.user.metas.company_url;
            },
            ebayUsername(){
                return this.user.metas.ebay_username;
            },
            isMySite(){
                if (this.ebayUsername !== null && this.sellerUsername !== null && this.ebayUsername === this.sellerUsername) {
                    return true;
                }

                if (this.companyUrl === null) {
                    return false;
                }

                let siteDomain = this.$options.filters.domain(this.site.siteUrl);
                let siteDomainParts = siteDomain.split('.');
                let siteSubdomain = siteDomainParts.shift();
                let upperLevelSiteDomain = siteDomainParts.join('.');

                let companyDomain = this.$options.filters.domain(this.companyUrl);
                let companyDomainParts = companyDomain.split('.');
                let companySubdomain = companyDomainParts.shift();
                let upperLevelCompanyDomain = companyDomainParts.join('.');

                if (upperLevelSiteDomain === upperLevelCompanyDomain) {
                    return true;
                }

                /*TODO check ebay stuff*/
                return false;
            },
            mySiteClass(){
                if (this.isMySite) {
                    return 'my-site';
                }
                return '';
            }
        },
    }
</script>

<style>
    .btn-edit.btn-edit-site {
        color: #aaa;
        font-size: 12px;
    }

    .btn-edit.btn-edit-site {
        position: absolute;
        right: 0;
        top: 0;
        margin: 0 !important;
        height: 100%;
    }

    .btn-edit.btn-edit-site .btn-edit-align-middle {
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        right: 0px;
    }

    tr.site-wrapper td.site-url {
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        word-wrap: break-word;
        white-space: nowrap;
        min-width: 225px;
    }

    tr.site-wrapper td.site-url a.site-url-link {
        padding-right: 50px !important;
    }

    td.url-container {
        -ms-word-wrap: break-word;
        word-wrap: break-word;
        -ms-word-break: break-all;
        word-break: break-all;
    }

    tr.site-wrapper td.col-recent-price,
    tr.site-wrapper td.col-previous-price,
    tr.site-wrapper td.col-price-change {
        padding-right: 25px;
    }

    td.my-site,
    td.my-site a {
        color: #43bda5;
        font-weight: bold;
    }

    @media (max-width: 991px) {
        tr.site-wrapper td.site-url a.site-url-link {
            padding-right: 15px !important;
        }
    }
</style>