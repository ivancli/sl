<template>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <p class="text-muted f-s-17 m-b-20">
                    You can set up Basic or Advanced real-time alerts so you can receive email notifications when your
                    competitors or channels change prices, beat your price or match a specific price point you nominate.
                </p>
                <p class="text-muted f-s-17 m-b-20">
                    <span class="text-danger">Important:</span> some Categories and Products have frequent price changes
                    and you'll be notified of each one of them, which might result in a large quantity of emails. If you
                    don't want to receive many emails, you might want to Schedule a Report instead.
                </p>
            </div>
        </div>


        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs ui-sortable-handle">
                <li :class="setTabActiveClass('alert-settings')">
                    <a href="#alert-settings" @click.prevent="setActiveTab('alert-settings')">
                        Alert Settings
                    </a>
                </li>
                <li :class="setTabActiveClass('alert-history')">
                    <a href="#alert-history" @click.prevent="setActiveTab('alert-history')">
                        Alert History
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="alert-settings" :class="setTabActiveClass('alert-settings')">
                    <settings></settings>
                </div>
                <div class="tab-pane" id="alert-history" :class="setTabActiveClass('alert-history')">
                    <history></history>
                </div>
            </div>
        </div>

    </section>
</template>

<script>
    import settings from './Settings.vue';
    import history from './History.vue';

    export default {
        components: {
            settings,
            history,
        },
        mounted() {
            console.log('Index component mounted.');
            this.setInitActiveTab();
            this.setInitHashWatcher();
        },
        data(){
            return {
                activeTab: 'alert-settings'
            };
        },
        methods: {
            setInitActiveTab() {
                if (window.location.hash != '') {
                    switch (window.location.hash) {
                        case "#alert-history":
                            this.activeTab = window.location.hash.replace('#', '');
                            break;
                        case "#alert-settings":
                        default:
                            this.activeTab = 'alert-settings';
                    }
                }
            },
            setInitHashWatcher(){
                window.addEventListener("hashchange", () => {
                    this.activeTab = window.location.hash.replace('#', '');
                }, false);
            },
            setTabActiveClass(tabName) {
                return tabName == this.activeTab ? 'active' : '';
            },
            setActiveTab(tabName) {
                this.activeTab = tabName;
                location.replace(window.location.href.split('#')[0] + '#' + tabName);
            }
        }
    }
</script>

<style>
    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #7ed0c0;
    }
</style>