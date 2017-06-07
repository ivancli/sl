<template>
    <!-- Main content -->
    <section class="content">

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs ui-sortable-handle">
                <li :class="setTabActiveClass('edit-profile')">
                    <a href="#edit-profile" @click.prevent="setActiveTab('edit-profile')">
                        Edit Profile
                    </a>
                </li>
                <li :class="setTabActiveClass('bulk-import')" v-if="subscriptionIsValid">
                    <a href="#bulk-import" @click.prevent="setActiveTab('bulk-import')">
                        Bulk Import
                    </a>
                </li>
                <li :class="setTabActiveClass('site-names')">
                    <a href="#site-names" @click.prevent="setActiveTab('site-names')">
                        Site Names
                    </a>
                </li>
                <li :class="setTabActiveClass('reset-password')">
                    <a href="#reset-password" @click.prevent="setActiveTab('reset-password')">
                        Reset Password
                    </a>
                </li>
                <li :class="setTabActiveClass('manage-subscription')" v-if="hasSubscription">
                    <a href="#manage-subscription" @click.prevent="setActiveTab('manage-subscription')">
                        Manage My Subscription
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="edit-profile" :class="setTabActiveClass('edit-profile')">
                    <edit-profile></edit-profile>
                </div>
                <div class="tab-pane" id="bulk-import" :class="setTabActiveClass('bulk-import')">
                    <bulk-import></bulk-import>
                </div>
                <div class="tab-pane" id="site-names" :class="setTabActiveClass('site-names')">
                    <site-names></site-names>
                </div>
                <div class="tab-pane" id="reset-password" :class="setTabActiveClass('reset-password')">
                    <reset-password></reset-password>
                </div>
                <div class="tab-pane" id="manage-subscription" :class="setTabActiveClass('manage-subscription')" v-if="hasSubscription">
                    <manage-subscription></manage-subscription>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import EditProfile from './EditProfile.vue';
    import ResetPassword from './ResetPassword.vue';
    import DisplaySettings from './DisplaySettings.vue';
    import BulkImport from '../product/BulkImport.vue';
    import SiteNames from './SiteName.vue';
    import ManageSubscription from './ManageSubscription.vue';

    export default {
        components: {
            EditProfile,
            ResetPassword,
            DisplaySettings,
            SiteNames,
            BulkImport,
            ManageSubscription
        },
        data(){
            return {
                activeTab: 'edit-profile'
            }
        },
        methods: {
            setInitActiveTab() {
                if (window.location.hash !== '') {
                    switch (window.location.hash) {
                        case "#reset-password":
                        case "#display-settings":
                        case "#bulk-import":
                        case "#site-names":
                            this.activeTab = window.location.hash.replace('#', '');
                            break;
                        case "#manage-subscription":
                            if (this.hasSubscription) {
                                this.activeTab = window.location.hash.replace('#', '');
                                break;
                            }
                        case "#edit-profile":
                        default:
                            this.activeTab = 'edit-profile';
                    }
                }
            },
            setInitHashWatcher(){
                window.addEventListener("hashchange", () => {
                    this.activeTab = window.location.hash.replace('#', '');
                }, false);
            },
            setTabActiveClass(tabName) {
                return tabName === this.activeTab ? 'active' : '';
            },
            setActiveTab(tabName) {
                this.activeTab = tabName;
                location.replace(window.location.href.split('#')[0] + '#' + tabName);
            }
        },
        mounted() {
            console.info('Index component is mounted');
            this.setInitActiveTab();
            this.setInitHashWatcher();
        },
        computed: {
            user(){
                return this.$store.getters.user;
            },
            hasSubscription(){
                return !this.user.isStaffMember && this.user.subscription && this.user.subscription.apiSubscription;
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
        }
    }
</script>

<style>
    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #7ed0c0;
    }
</style>