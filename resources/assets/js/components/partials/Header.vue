<template>
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <span class="logo-mini">
                <img src="../../../images/logos/favicon.png" alt="SpotLite Logo Icon">
            </span>
            <span class="logo-lg">
                <img src="../../../images/logos/logo_transparent_white_text.png" alt="SpotLite Logo">
            </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" @click.prevent="toggleSidebar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li :class="dropdownToggle['need_help'] == true ? 'open' : ''">
                        <a href="#" class="dropdown-toggle lnk-drop-down-need-help"
                           @click.prevent="toggleDropdown('need_help')">
                            <i class="fa fa-question-circle"></i>
                            &nbsp;NEED HELP ?
                            &nbsp;&nbsp;&nbsp;
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">
                                SpotLite Tour
                            </a>
                            </li>
                            <li>
                                <a href="#" target="_blank">Video
                                    Tutorials</a></li>
                            <li><a href="#" target="_blank">FAQ</a></li>
                            <li>
                                <a href="#" target="_blank">Step by Step Guide</a>
                            </li>
                            <li><a href="#">Contact us</a></li>
                        </ul>
                    </li>
                    <li class="spotlite-user-menu">
                        <a href="#">
                            <i class="fa fa-wrench"></i>&nbsp;
                            <span class="hidden-xs">
                                ACCOUNT SETTINGS
                            </span>&nbsp;
                        </a>
                    </li>
                    <li>
                        <a href="/logout">
                            <i class="fa fa-sign-in"></i>&nbsp;
                            SIGN OUT
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
    import {
            TOGGLE_SIDEBAR
    } from '../../actions/mutation-types';

    export default {
        mounted() {
            console.log('Header component mounted.')
        },
        data: ()=> {
            return {
                dropdownToggle: {
                    'need_help': false,
                }
            }
        },
        methods: {
            toggleDropdown: function (current_index) {
                for (var index in this.dropdownToggle) {
                    if (index != current_index && this.dropdownToggle.hasOwnProperty(index)) {
                        this.dropdownToggle[index] = false;
                    }
                }
                this.dropdownToggle[current_index] = !this.dropdownToggle[current_index];
            },
            toggleSidebar: function () {
                this.$store.commit(TOGGLE_SIDEBAR);
            },
        }
    }
</script>

<style>
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

    @media (min-width: 991px) {
        .navbar-custom-menu {
            padding-top: 35px;
            padding-bottom: 35px;
        }
    }

    @media (min-width: 768px) {
        .navbar-nav > li > a {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .skin-black-light .main-header .navbar > .sidebar-toggle {
            padding-top: 40px;
            padding-bottom: 40px;
        }
    }
</style>