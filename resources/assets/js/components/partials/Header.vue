<template>
    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="navbar-header">
                <a href="/" class="navbar-brand">
                    <img src="/images/favicon.png" alt="SpotLite" style="max-height: 40px;">
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="">
                        <a href="/dashboard">
                            <i class="fa fa-dashboard"></i>&nbsp;DASHBOARDS
                        </a>
                    </li>
                    <li class="">
                        <a href="/product">
                            <i class="fa fa-tag"></i>&nbsp;PRODUCTS
                        </a>
                    </li>
                    <li class="" v-if="subscriptionIsValid">
                        <a href="/positioning">
                            <i class="fa fa-street-view"></i>&nbsp;POSITIONING
                        </a>
                    </li>
                    <li class="" v-if="subscriptionIsValid">
                        <a href="/alert">
                            <i class="fa fa-bell-o"></i>&nbsp;ALERTS
                        </a>
                    </li>
                    <li class="" v-if="subscriptionIsValid">
                        <a href="/report">
                            <i class="fa fa-envelope-o"></i>&nbsp;REPORTS
                        </a>
                    </li>
                    <li class="" v-if="isStaffMember">
                        <a href="/app-preference">
                            <i class="fa fa-gears"></i>
                            <span class="hidden-lg hidden-md hidden-sm">App Preferences</span>
                        </a>
                    </li>
                    <li class="dropdown" v-if="isStaffMember">
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-files-o"></i>&nbsp;<span class="hidden-lg hidden-md hidden-sm">Manage Crawler</span>&nbsp;<i
                                class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="">
                                <a href="/url-management/domain">
                                    <i class="fa fa-circle-o"></i> Domains
                                </a>
                            </li>
                            <li class="">
                                <a href="/url-management/url">
                                    <i class="fa fa-circle-o"></i> URLs
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown " v-if="isStaffMember">
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-users"></i>&nbsp;<span
                                class="hidden-lg hidden-md hidden-sm">Manage Users</span>&nbsp;<i
                                class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="">
                                <a href="/user-management/user">
                                    <i class="fa fa-user"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/user-management/group">
                                    <i class="fa fa-users"></i>
                                    <span>Groups</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/user-management/role">
                                    <i class="fa fa-tags"></i>
                                    <span>Roles</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/user-management/permission">
                                    <i class="fa fa-key"></i>
                                    <span>Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown " v-if="isStaffMember">
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-file-text-o"></i>&nbsp;<span class="hidden-lg hidden-md hidden-sm">System Logs</span>&nbsp;<i
                                class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="">
                                <a href="#">
                                    <i class="fa fa-gear"></i>
                                    <span>Crawler Logs</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/log/user-activity-log">
                                    <i class="fa fa-map-o"></i>
                                    <span>User Activity Logs</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown" v-if="isStaffMember">
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-file-archive-o"></i>&nbsp;<span class="hidden-lg hidden-md hidden-sm">Manage Legals</span>&nbsp;<i
                                class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="">
                                <a href="https://login.spotlite.com.au/term_and_condition">
                                    <i class="fa fa-square"></i>
                                    <span>Terms and Conditions</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://login.spotlite.com.au/privacy_policy">
                                    <i class="fa fa-square"></i>
                                    <span>Privacy Policies</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <product-filter v-if="isProductPage"></product-filter>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle lnk-drop-down-account" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog"></i>
                            <span class="hidden-xs">&nbsp;&nbsp;<i class="fa fa-caret-down"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/account-settings#edit-profile">My Account</a></li>
                            <li><a href="/account-settings#bulk-import">Bulk Import <span class="icon-new-feature">NEW</span></a></li>
                            <li><a href="/account-settings#site-names">Site Names</a></li>
                            <li><a href="/account-settings#reset-password">Reset Password</a></li>
                            <li v-if="needSubscription && hasSubscription"><a href="/account-settings#manage-subscription">Manage My Subscription</a></li>
                            <li><a href="/logout">Logout</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle lnk-drop-down-need-help" data-toggle="dropdown"
                           aria-expanded="false">
                            <i class="fa fa-question-circle"></i>
                            <span class="hidden-xs">&nbsp;&nbsp;<i class="fa fa-caret-down"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">SpotLite Tour</a></li>
                            <li><a href="#" target="_blank">Video Tutorials</a></li>
                            <li><a href="#" target="_blank">FAQ</a></li>
                            <li><a href="#" target="_blank">Step by Step Guide</a></li>
                            <li><a href="#">Contact us</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
    import productFilter from '../app/product/Filter.vue'

    import {
        TOGGLE_SIDEBAR
    } from '../../actions/action-types';

    export default {
        components: {
            productFilter
        },
        mounted() {
            console.log('Header component mounted.')
        },
        data(){
            return {}
        },
        methods: {},
        computed: {
            isProductPage(){
                if (typeof currentRouteName !== 'undefined') {
                    return currentRouteName === 'product.index';
                } else {
                    return window.location.pathname === "/product";
                }
            },
            needSubscription(){
                return user.needSubscription;
            },
            hasSubscription(){
                return typeof user.subscription !== 'undefined' && user.subscription !== null;
            },
            user(){
                return this.$store.getters.user
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
            isStaffMember(){
                return this.user.isStaffMember === true;
            }
        }
    }
</script>

<style>
    .skin-black-light .main-header .navbar-brand {
        border-right: 1px solid #8cdacb;
    }

    .skin-black-light .main-header .navbar .navbar-nav > li > a {
        border-right: 1px solid #8cdacb;
    }

    .skin-black-light .main-header .navbar .navbar-custom-menu .navbar-nav > li > a, .skin-black-light .main-header .navbar .navbar-right > li > a {
        border-left: 1px solid #8cdacb;
    }

    .skin-black-light .main-header {
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }

    header.main-header a.logo {
        height: 100px;
    }

    .main-header {
        max-height: none;
    }

    header.main-header a.logo img {
        max-width: 100%;
        max-height: 100%;
        padding-left: 5px;
        padding-right: 5px;
    }

    header.main-header a.logo span.logo-lg {
        height: 100%;
    }

    header.main-header a.logo span.logo-lg img {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    header.main-header a.logo span.logo-mini img {
        padding-top: 30px;
        padding-bottom: 30px;
    }

    .skin-black-light .main-header .navbar {
        background-color: #7ed0c0;
    }

    .skin-black-light .main-header > .logo {
        background-color: #7ed0c0;
        border-right: 1px solid #7ed0c0;
    }

    .skin-black-light .main-header > .logo:hover {
        background-color: #7ed0c0;
        border-right: 1px solid #7ed0c0;
    }

    .skin-black-light .main-header .navbar > .sidebar-toggle,
    .skin-black-light .main-header .navbar .sidebar-toggle:hover {
        background: #7ed0c0;
        color: #fff;
    }

    .skin-black-light .main-header .navbar > .sidebar-toggle {
        border-right: none;
    }

    .skin-black-light .wrapper, .skin-black-light .main-sidebar, .skin-black-light .left-side {
        background-color: #7ed0c0;
    }

    .skin-black-light .main-header .navbar .nav > li > a {
        color: #fff;
    }

    .skin-black-light .main-header .navbar .navbar-custom-menu .navbar-nav > li:first-child > a {
        border-left: none;
    }

    .skin-black-light .main-header .navbar .nav > li > a:hover, .skin-black-light .main-header .navbar .nav > li > a:active, .skin-black-light .main-header .navbar .nav > li > a:focus, .skin-black-light .main-header .navbar .nav .open > a, .skin-black-light .main-header .navbar .nav .open > a:hover, .skin-black-light .main-header .navbar .nav .open > a:focus, .skin-black-light .main-header .navbar .nav > .active > a {
        background: #7ed0c0;
        color: #fff;
    }

    .icon-new-feature {
        font-size: 10px;
        color: #00d200;
        font-weight: bold;
        position: absolute;
        padding-left: 3px;
    }

    .skin-black-light .main-header .navbar-toggle {
        color: #fff;
    }

    @media (min-width: 768px) {
        .skin-black-light .main-header .navbar > .sidebar-toggle {
            padding-top: 40px;
            padding-bottom: 40px;
        }
    }

    @media (max-width: 767px) {
        .skin-black-light .main-header .navbar .navbar-custom-menu .navbar-nav > li > a, .skin-black-light .main-header .navbar .navbar-right > li > a {
            border: none;
        }
    }
</style>