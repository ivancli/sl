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
        <td align="center" class="vertical-align-middle hidden-xs hidden-sm">
            <a href="#" class="btn-my-price">
                <i class="fa fa-check-circle-o text-primary"></i>
            </a>
        </td>
        <td class="vertical-align-middle">
            <div class="text-right">
                <!--$368.00-->
            </div>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm">
            <div class="text-right">
                <div class="p-r-30">
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm">
            <div class="text-right">
                <div class="p-r-10">
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="hidden-xs vertical-align-middle hidden-xs hidden-sm" style="padding-left: 20px;">
            <div class="p-l-30">
                <strong><i class="fa fa-minus"></i></strong>
            </div>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm">
            <span title="2017-02-14 3:10 pm" data-toggle="tooltip">
                2017-02-14
                <span class="hidden-xs hidden-sm">3:10 pm</span>
            </span>
        </td>
        <td class="vertical-align-middle hidden-xs hidden-sm">
            <div :title="site.created_at | formatDateTime(datetimeFormat)">
                {{site.created_at | formatDateTime(dateFormat)}}
            </div>
        </td>
        <td class="text-right action-cell vertical-align-middle">
            <a href="#" class="btn-action hidden-md hidden-lg" title="show details"
               @click.prevent="toggleSiteDetails">
                <i class="fa fa-info-circle"></i>
            </a>
            <a href="#" class="btn-action" title="chart">
                <i class="fa fa-line-chart"></i>
            </a>
            <a href="#" class="btn-action" title="delete">
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
                    <td v-text="site.siteUrl"></td>
                </tr>
                <tr>
                    <th>Is my site:</th>
                    <td>Yes</td>
                </tr>
                <tr>
                    <th>Current price:</th>
                    <td>$123.45</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</template>

<script>
    import formatDateTime from '../../../filters/formatDateTime';
    import domain from '../../../filters/domain';

    import editSite from './EditSite.vue';

    export default {
        components: {
            editSite
        },
        props: [
            'current-site'
        ],
        data() {
            return {
                editingSiteURL: false,
                showSiteDetails: false,
            }
        },
        mounted() {

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
            }
        },
        computed: {
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
            },
            site(){
                return this.currentSite;
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
        position: relative;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    tr.site-wrapper td.site-url {
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        word-wrap: break-word;
        white-space: nowrap;
        min-width: 225px;
    }

    tr.site-wrapper td.site-url a {
        padding-right: 50px !important;
    }
</style>