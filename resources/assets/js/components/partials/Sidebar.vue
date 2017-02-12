<template>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="treeview">
                    <a href="#">
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
                <li class="">
                    <a href="#">
                        <i class="fa fa-gears"></i>
                        <span>APP PREFERENCES</span>
                    </a>
                </li>
                <li class="treeview  ">
                    <a href="#">
                        <i class="fa fa-files-o"></i>
                        <span>Manage Crawler</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
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
                <li class="treeview ">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>USER MANAGEMENT</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="#">
                                <i class="fa fa-user"></i>
                                <span>Users</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-users"></i>
                                <span>Groups</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-tags"></i>
                                <span>Roles</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-key"></i>
                                <span>Permissions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview ">
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
                <li class="treeview  ">
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
    export default {
        mounted() {
            console.log('Header component mounted.')
        },
        methods: {
            topLevelActiveClass: (link)=> {
                return window.location.pathname.startsWith(link) ? 'active' : '';
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

    @media (min-width: 768px) {
        .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > a > span:not(.pull-right), .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > .treeview-menu {
            width: 220px;
        }

        .sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > a > .pull-right-container {
            left: 220px !important;
        }
    }
</style>