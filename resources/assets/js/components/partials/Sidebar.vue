<template>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="treeview">
                    <a href="#" @click.prevent="toggleDropDownItems('dashboard')">
                        <i class="fa fa-dashboard"></i>
                        <span>DASHBOARDS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="#" class="lnk-dashboard">
                                <i class="fa fa-circle-o"></i>
                                <span class="lnk-dashboard-104">
                                    Default Dashboard
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" id="btn-add-new-dashboard">
                        <i class="fa fa-plus"></i>
                        <span>ADD A NEW DASHBOARD</span>
                    </a>
                </li>
                <li :class="topLevelActiveClass('/product')">
                    <a href="/product">
                        <i class="fa fa-tag"></i>
                        <span>PRODUCTS</span>
                    </a>
                </li>
                <li :class="topLevelActiveClass('/alert')">
                    <a href="/alert">
                        <i class="fa fa-bell-o"></i>
                        <span>ALERTS</span></a>
                </li>
                <li :class="topLevelActiveClass('/report')">
                    <a href="/report">
                        <i class="fa fa-envelope-o"></i>
                        <span>REPORTS</span>
                    </a>
                </li>
                <li class="" v-if="isStaffMember">
                    <a href="#">
                        <i class="fa fa-gears"></i>
                        <span>APP PREFERENCES</span>
                    </a>
                </li>
                <li class="treeview" :class="topLevelActiveClass('/manage-crawler')" v-if="isStaffMember">
                    <a href="#" @click.prevent="toggleDropDownItems('/manage-crawler')">
                        <i class="fa fa-files-o"></i>
                        <span>Manage Crawler</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu"
                        :class="dropDownItemVisibility['/manage-crawler'] ? 'menu-open' : ''"
                        v-show="dropDownItemVisibility['/manage-crawler']">
                        <li class="">
                            <a href="#">
                                <i class="fa fa-circle-o"></i> Domains
                            </a>
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-circle-o"></i> Sites
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview" :class="topLevelActiveClass('/user-management')" v-if="isStaffMember">
                    <a href="#" @click.prevent="toggleDropDownItems('/user-management')">
                        <i class="fa fa-users"></i>
                        <span>USER MANAGEMENT</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" :class="dropDownItemVisibility['/user-management'] ? 'menu-open' : ''"
                        v-show="dropDownItemVisibility['/user-management']">
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
                <li class="treeview" v-if="isStaffMember">
                    <a href="#">
                        <i class="fa fa-file-text-o"></i>
                        <span>SYSTEM LOG</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="#">
                                <i class="fa fa-gear"></i>
                                <span>Crawler Logs</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-map-o"></i>
                                <span>User Activity Logs</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview" v-if="isStaffMember">
                    <a href="#">
                        <i class="fa fa-file-archive-o"></i>
                        <span>MANAGE LEGALS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="#">
                                <i class="fa fa-square"></i>
                                <span>Terms and Conditions</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-square"></i>
                                <span>Privacy Policies</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
    /* TODO top level active class needs to be refined */
    /* TODO now active top level item caret does not rotate while collapsing its child-list */

    export default {
        data(){
            return {
                dropDownItemVisibility: {
                    '/dashboard': false,
                    '/manage-crawler': false,
                    '/user-management': false,
                    '/system-log': false,
                    '/manage-legal': false,
                }
            }
        },
        mounted() {
            console.log('Header component mounted.')
            this.initAssignDropDownItemsVisibility();
        },
        methods: {
            initAssignDropDownItemsVisibility(){
                for (var key in this.dropDownItemVisibility) {
                    if (this.dropDownItemVisibility.hasOwnProperty(key)) {
                        if (this.topLevelActiveClass(key) == 'active') {
                            this.dropDownItemVisibility[key] = true;
                        }
                    }
                }
            },
            topLevelActiveClass(link) {
                return window.location.pathname.startsWith(link) || this.dropDownItemVisibility[link] ? 'active' : '';
            },
            toggleDropDownItems(key) {
                if (this.dropDownItemVisibility.hasOwnProperty(key)) {
                    if (this.dropDownItemVisibility[key] == true) {
                        this.dropDownItemVisibility[key] = false;
                    } else {
                        for (var dropdownItemKey in this.dropDownItemVisibility) {
                            if (this.dropDownItemVisibility.hasOwnProperty(dropdownItemKey)) {
                                this.dropDownItemVisibility[dropdownItemKey] = false;
                            }
                        }
                        this.dropDownItemVisibility[key] = true;
                    }
                }
            }
        },
        computed: {
            isStaffMember(){
                if (typeof user != 'undefined') {
                    return user.isStaffMember;
                }
                return false;
            }
        }
    }
</script>

<style>
    .main-sidebar, .left-side {
        padding-top: 100px;
    }

    .skin-black-light .sidebar a {
        color: #fff;
    }

    .skin-black-light .sidebar-menu > li:hover > a, .skin-black-light .sidebar-menu > li.active > a {
        color: #fff;
        background: #6dbdad;
    }

    .skin-black-light .sidebar-menu > li > .treeview-menu {
        background: #6dbdad;
    }

    .skin-black-light .treeview-menu > li > a {
        color: #fff
    }

    .skin-black-light .treeview-menu > li.active > a, .skin-black-light .treeview-menu > li > a:hover {
        color: #fff;
    }

    .sidebar-menu li.treeview.active > a > .fa-caret-down, .sidebar-menu li.treeview.active > a > .pull-right-container > .fa-caret-down {
        transform: rotate(-180deg);
        -moz-transform: rotate(-180deg);
        -ms-transform: rotate(-180deg);
        -o-transform: rotate(-180deg);
        -webkit-transform: rotate(-180deg);
        transition: transform 550ms ease;
        -moz-transition: -moz-transform 550ms ease;
        -ms-transition: -ms-transform 550ms ease;
        -o-transition: -o-transform 550ms ease;
        -webkit-transition: -webkit-transform 550ms ease;
    }

    @media (min-width: 768px) {
        .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > a > span:not(.pull-right), .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > .treeview-menu {
            width: 220px;
        }

        .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > a > .pull-right-container {
            left: 220px !important;
        }
    }
</style>