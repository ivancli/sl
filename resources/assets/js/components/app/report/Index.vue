<template>
    <!-- Main content -->
    <section class="content">

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs ui-sortable-handle">
                <li :class="setTabActiveClass('report-settings')">
                    <a href="#report-settings" @click.prevent="setActiveTab('report-settings')">
                        Report Settings
                    </a>
                </li>
                <li :class="setTabActiveClass('report-history')">
                    <a href="#report-history" @click.prevent="setActiveTab('report-history')">
                        Report History
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="report-settings" :class="setTabActiveClass('report-settings')">
                    <settings></settings>
                </div>
                <div class="tab-pane" id="report-history" :class="setTabActiveClass('report-history')">
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
        data(){
            return {
                activeTab: 'report-settings'
            }
        },
        mounted() {
            console.info('Index component is mounted');
            this.setInitActiveTab();
            this.setInitHashWatcher();
        },
        methods: {
            setInitActiveTab() {
                if (window.location.hash !== '') {
                    switch (window.location.hash) {
                        case "#report-history":
                            this.activeTab = window.location.hash.replace('#', '');
                            break;
                        case "#report-settings":
                        default:
                            this.activeTab = 'report-settings';
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
        computed: {

        }
    }
</script>

<style>
    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #7ed0c0;
    }
</style>