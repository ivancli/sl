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
                <li :class="setTabActiveClass('reset-password')">
                    <a href="#reset-password" @click.prevent="setActiveTab('reset-password')">
                        Reset Password
                    </a>
                </li>
                <li :class="setTabActiveClass('display-settings')">
                    <a href="#display-settings" @click.prevent="setActiveTab('display-settings')">
                        Display Settings
                    </a>
                </li>
                <li :class="setTabActiveClass('manage-subscription')">
                    <a href="#display-settings" @click.prevent="setActiveTab('manage-subscription')">
                        Manage My Subscription
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="edit-profile" :class="setTabActiveClass('edit-profile')">
                    <edit-profile></edit-profile>
                </div>
                <div class="tab-pane" id="reset-password" :class="setTabActiveClass('reset-password')">
                    <reset-password></reset-password>
                </div>
                <div class="tab-pane" id="display-settings" :class="setTabActiveClass('display-settings')">
                    <display-settings></display-settings>
                </div>
                <div class="tab-pane" id="manage-subscription" :class="setTabActiveClass('manage-subscription')">
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
    import ManageSubscription from './ManageSubscription.vue';

    export default {
        components: {
            EditProfile,
            ResetPassword,
            DisplaySettings,
            ManageSubscription
        },
        data(){
            return {
                activeTab: 'edit-profile'
            }
        },
        methods: {
            setTabActiveClass: function (tabName) {
                return tabName == this.activeTab ? 'active' : '';
            },
            setActiveTab: function (tabName) {
                this.activeTab = tabName;
            }
        },
        mounted() {
            console.info('Index component is mounted');
            /**
             * set active tab based on URL hash value
             */
            if (window.location.hash != '') {
                switch (window.location.hash) {
                    case "#reset-password":
                    case "#display-settings":
                        this.activeTab = window.location.hash.replace('#', '');
                        break;
                    case "#edit-profile":
                    default:
                        this.activeTab = 'edit-profile';
                }
            }
        }
    }
</script>

<style>
    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #7ed0c0;
    }
</style>