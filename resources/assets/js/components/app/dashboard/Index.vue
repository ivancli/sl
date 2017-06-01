<template>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <p class="text-muted f-s-17 m-b-20">
                    <small>
                        Created on the {{ user.created_at | formatDateTime(dateFormat) }} <strong><i> by {{ user.fullName }}</i></strong>
                        &vert;
                        <a href="#" class="text-muted" @click.prevent="onClickAddChartToDashboard">Add Chart to Dashboard <i class="fa fa-cog"></i></a>
                    </small>
                </p>
                <p class="text-muted f-s-17 m-b-20">
                    Welcome to your Dashboard. Here you'll be able to see all prices you're tracking through automatically updated charts. Add a product price you want to track by clicking on "Add Product to Track" link or go to the Products page on the navigation to add your Categories & Products.
                </p>
            </div>
        </div>
        <hr class="content-divider-white">
        <div class="row text-muted">
            <div class="col-sm-12">
                <form class="form-inline">
                    <div class="form-group">
                        FILTER BY:
                    </div>
                    &nbsp;&nbsp;
                    <div class="form-group">
                        <select class="form-control sl-form-control" v-model="globalTimespan">
                            <option value="">-- select timespan --</option>
                            <option value="this_week">this week</option>
                            <option value="last_week">last week</option>
                            <option value="last_7_days">last 7 days</option>
                            <option value="this_month">this month</option>
                            <option value="last_month">last month</option>
                            <option value="last_30_days">last 30 days</option>
                            <option value="this_quarter">this quarter</option>
                            <option value="last_quarter">last quarter</option>
                            <option value="last_90_days">last 90 days</option>
                        </select>
                    </div>
                    &nbsp;&nbsp;
                    <div class="form-group">
                        <select class="form-control sl-form-control" v-model="globalResolution">
                            <option value="">-- select period resolution --</option>
                            <option value="day">day</option>
                            <option value="week">week</option>
                            <option value="month">month</option>
                        </select>
                    </div>
                    &nbsp;&nbsp;
                    <div class="form-group">
                        <button class="btn btn-default btn-flat" @click.prevent="resetFilter">RESET FILTER</button>
                    </div>
                </form>
            </div>
        </div>
        <hr class="content-divider-white">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-3" v-for="widget in widgets">
                        <category-widget v-if="widget.widgetable_type === 'category'" :widget="widget" :global-timespan="globalTimespan" :global-resolution="globalResolution" @deleted-widget="loadWidgets"></category-widget>
                        <product-widget v-if="widget.widgetable_type === 'product'" :widget="widget" :global-timespan="globalTimespan" :global-resolution="globalResolution" @deleted-widget="loadWidgets"></product-widget>
                        <site-widget v-if="widget.widgetable_type === 'site'" :widget="widget" :global-timespan="globalTimespan" :global-resolution="globalResolution" @deleted-widget="loadWidgets"></site-widget>
                    </div>
                    <div class="col-md-3">
                        <placeholder :should-redirect="shouldRedirect" @add-new-widget="addNewWidget"></placeholder>
                    </div>
                </div>
            </div>
        </div>
        <add-widget v-if="isAddingWidget" @added-widget="addedNewWidget" @hide-modal="cancelAddNewWidget"></add-widget>
        <create-password v-if="!hasSetPassword" @set-password="loadUser"></create-password>
        <welcome v-if="hasSetPassword && !hasSetSamples" @set-welcome="setWelcome"></welcome>
    </section>
</template>

<script>
    import siteWidget from './widgets/product/Site.vue';
    import productWidget from './widgets/product/Product.vue';
    import categoryWidget from './widgets/product/Category.vue';
    import placeholder from './widgets/Placeholder.vue';
    import addWidget from './widgets/AddWidget.vue';
    import createPassword from '../account_settings/popups/CreatePassword.vue';
    import welcome from '../account_settings/popups/Welcome.vue';

    import formatDateTime from '../../../filters/formatDateTime';

    import {
        LOAD_USER
    } from '../../../actions/action-types';

    export default{
        components: {
            siteWidget,
            productWidget,
            categoryWidget,
            placeholder,
            addWidget,
            createPassword,
            welcome,
        },
        mounted(){
            console.info('Index component mounted.');
            this.loadWidgets();
        },
        data(){
            return {
                widgets: [],
                globalTimespan: '',
                globalResolution: '',
                isAddingWidget: false,
                isLoadingWidget: true,
            }
        },
        methods: {
            loadUser(){
                this.$store.dispatch(LOAD_USER);
            },
            loadWidgets(){
                this.isLoadingWidget = true;
                axios.get('/dashboard/widget').then(response => {
                    this.isLoadingWidget = false;
                    if (response.data.status === true) {
                        this.widgets = response.data.widgets;
                    }
                }).catch(error => {
                    this.isLoadingWidget = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                });
            },
            onClickAddChartToDashboard(){
                if (this.shouldRedirect === true) {
                    window.location.href = '/product';
                } else {
                    this.addNewWidget();
                }
            },
            addNewWidget(){
                this.isAddingWidget = true;
            },
            addedNewWidget(){
                this.cancelAddNewWidget();
                this.loadWidgets();
            },
            cancelAddNewWidget(){
                this.isAddingWidget = false;
            },
            resetFilter(){
                this.globalTimespan = '';
                this.globalResolution = '';
            },
            setWelcome(){
                this.loadUser();
                this.loadWidgets();
            },
        },
        computed: {
            user(){
                return this.$store.getters.user;
            },
            dateFormat(){
                return this.user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return this.user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
            },
            shouldRedirect(){
                return this.user.numberOfSites === 0
            },
            globalFrom(){
                let startDate = null;
                switch (this.globalTimespan) {
                    case "this_week":
                        startDate = moment().startOf('isoweek').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_week":
                        startDate = moment().subtract(1, 'week').startOf('isoweek').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_7_days":
                        startDate = moment().subtract(7, 'day').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_month":
                        startDate = moment().startOf("month").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_month":
                        startDate = moment().subtract(1, 'month').startOf("month").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_30_days":
                        startDate = moment().subtract(30, 'day').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_quarter":
                        startDate = moment().startOf("quarter").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_quarter":
                        startDate = moment().subtract(1, 'quarter').startOf("quarter").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_90_days":
                        startDate = moment().subtract(90, 'day').format("YYYY-MM-DD HH:mm:ss");
                        break;
                }
                return startDate;
            },
            globalTo(){
                let endDate = null;
                switch (this.globalTimespan) {
                    case "this_week":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_week":
                        endDate = moment().subtract(1, 'week').endOf('isoweek').format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_7_days":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_month":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_month":
                        endDate = moment().subtract(1, 'month').endOf("month").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_30_days":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "this_quarter":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_quarter":
                        endDate = moment().subtract(1, 'quarter').endOf("quarter").format("YYYY-MM-DD HH:mm:ss");
                        break;
                    case "last_90_days":
                        endDate = moment().format("YYYY-MM-DD HH:mm:ss");
                        break;
                }
                return endDate;
            },
            globalFilterRequestData(){
                return {
                    from: this.globalFrom,
                    to: this.globalTo,
                    resolution: this.globalResolution,
                }
            },
            hasSetPassword(){
                return this.user.set_password === 'y';
            },
            hasSetSamples(){
                return this.user.set_samples === 'y';
            },
        }
    }
</script>

<style>
    .content-divider-white {
        border-top: 1px solid #fff;
    }
</style>