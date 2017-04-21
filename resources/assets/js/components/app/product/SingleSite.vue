<template>
    <tbody>
    <tr class="site-wrapper">
        <td class="site-url vertical-align-middle">
            <a :href="site.siteUrl" target="_blank" class="text-muted site-url-link" :title="site.siteUrl"
               v-show="!editingSiteURL">
                {{site.siteUrl | domain}}
            </a>

            <edit-site :editing-site="site" @edited-site="editedSite" @edit-site-url="goingToEditSiteURL"
                       @cancel-edit-site-url="cancelEditSiteURL"></edit-site>
        </td>
        <td class="vertical-align-middle">
            <div class="text-right">
                <div v-if="site.item != null && site.item.recentPrice != null">
                    ${{ site.item.recentPrice | currency }}
                </div>
                <div v-else>
                    <div class="p-r-30">
                        <strong><i class="fa fa-minus"></i></strong>
                    </div>
                </div>
            </div>
        </td>
        <td class="vertical-align-middle">
            <div class="text-center">
                <div v-if="site.item != null && site.item.availability != null">
                    {{ site.item.availability == true ? 'Yes' : 'No' }}
                </div>
                <div v-else>
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm">
            <div class="text-right">
                <div v-if="site.item != null && site.item.previousPrice != null">
                    ${{ site.item.previousPrice | currency }}
                </div>
                <div v-else>
                    <div class="p-r-30">
                        <strong><i class="fa fa-minus"></i></strong>
                    </div>
                </div>
            </div>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm">
            <div class="text-right">
                <div v-if="site.item != null && site.item.priceChange != null">
                    ${{ site.item.priceChange | currency }}
                </div>
                <div v-else>
                    <div class="p-r-10">
                        <strong><i class="fa fa-minus"></i></strong>
                    </div>
                </div>
            </div>
        </td>
        <td class="hidden-xs vertical-align-middle hidden-xs hidden-sm" style="padding-left: 20px;">
            <div v-if="site.item != null && site.item.lastChangedAt != null">
                {{ site.item.lastChangedAt | formatDateTime(dateFormat) }}
            </div>
            <div v-else>
                <div class="p-l-30">
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="text-right action-cell vertical-align-middle">
            <a href="#" class="btn-action hidden-md hidden-lg" title="show details"
               @click.prevent="toggleSiteDetails">
                <i class="fa fa-info-circle"></i>
            </a>
            <a href="#" class="btn-action" title="choose item" v-if="site.url.itemsCount > 1">
                <i class="fa fa-list-ul"></i>
            </a>
            <a href="#" class="btn-action" title="chart">
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
                    <td class="url-container" v-text="site.siteUrl"></td>
                </tr>
                <tr>
                    <th>Is my site:</th>
                    <td>Yes</td>
                </tr>
                <tr>
                    <th>Current price:</th>
                    <td>
                        <div v-if="site.item != null && site.item.recentPrice != null">
                            ${{ site.item.recentPrice | currency }}
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
    <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete"
                         @confirmDelete="confirmDelete"></delete-confirmation>
    </tbody>
</template>

<script>
    import formatDateTime from '../../../filters/formatDateTime';
    import currency from '../../../filters/currency';
    import domain from '../../../filters/domain';

    import editSite from './EditSite.vue';

    import deleteConfirmation from '../../fragments/modals/DeleteConfirmation.vue';

    export default {
        components: {
            editSite,
            deleteConfirmation,
        },
        props: [
            'current-site'
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
                isDeletingSite: false
            }
        },
        mounted() {
            console.info('SingleSite component is mounted');
        },
        methods: {
            goingToEditSiteURL: function () {
                this.editingSiteURL = true;
            },
            editedSite: function () {
                this.editingSiteURL = false;
                this.reloadSites();
            },
            cancelEditSiteURL: function () {
                this.editingSiteURL = false;
            },
            reloadSites: function () {
                this.$emit('reload-sites');
            },
            toggleSiteDetails: function () {
                this.showSiteDetails = !this.showSiteDetails;
            },
            /*delete site*/
            onClickDeleteSite(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteSite()
            },
            deleteSite(){
                this.isDeletingSite = true;
                axios.delete(this.site.urls.delete).then(response => {
                    this.isDeletingSite = false;
                    if (response.data.status == true) {
                        this.$emit('deleted-site');
                    }
                }).catch(error => {
                    this.isDeletingSite = false;
                })
            }
        },
        computed: {
            site(){
                return this.currentSite;
            },
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
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

    @media (max-width: 991px) {
        tr.site-wrapper td.site-url a.site-url-link {
            padding-right: 15px !important;
        }
    }
</style>