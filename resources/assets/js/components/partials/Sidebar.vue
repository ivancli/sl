<template>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="treeview  ">
                    <a href="#">
                        <i class="fa fa-dashboard"></i>
                        <span>DASHBOARDS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="http://login.spotlite.com.au/dashboard/104" class="lnk-dashboard">
                                <i class="fa fa-circle-o"></i>
                                <span class="lnk-dashboard-104">
                                    Default Dashboard
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" onclick="showAddDashboardForm(this); return false;" id="btn-add-new-dashboard">
                        <i class="fa fa-plus"></i>
                        <span>ADD A NEW DASHBOARD</span>
                    </a>
                </li>
                <li class="active lnk-product">
                    <a href="http://login.spotlite.com.au/product">
                        <i class="fa fa-tag"></i>
                        <span>PRODUCTS</span>
                    </a>
                </li>
                <li class=" lnk-alert">
                    <a href="http://login.spotlite.com.au/alert">
                        <i class="fa fa-bell-o"></i>
                        <span>ALERTS</span></a>
                </li>
                <li class="">
                    <a href="http://login.spotlite.com.au/report">
                        <i class="fa fa-envelope-o"></i>
                        <span>REPORTS</span>
                    </a>
                </li>
                <li class="">
                    <a href="http://login.spotlite.com.au/admin/app_preference">
                        <i class="fa fa-gears"></i>
                        <span>APP PREFERENCES</span>
                    </a>
                </li>
                <li class="treeview  ">
                    <a href="#">
                        <i class="fa fa-files-o"></i>
                        <span>CRAWLER MANAGEMENT</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="http://login.spotlite.com.au/admin/domain">
                                <i class="fa fa-circle-o"></i> Domains
                            </a>
                        </li>
                        <li class="">
                            <a href="http://login.spotlite.com.au/admin/site">
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
                            <a href="http://login.spotlite.com.au/um/user">
                                <i class="fa fa-user"></i>
                                <span>Users</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="http://login.spotlite.com.au/um/group">
                                <i class="fa fa-users"></i>
                                <span>Groups</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="http://login.spotlite.com.au/um/role">
                                <i class="fa fa-tags"></i>
                                <span>Roles</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="http://login.spotlite.com.au/um/permission">
                                <i class="fa fa-key"></i>
                                <span>Permissions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview ">
                    <a href="#">
                        <i class="fa fa-file-text-o"></i>
                        <span>SYSTEM LOG MANAGEMENT</span>
                        <span class="pull-right-container">
                            <i class="fa fa-caret-down pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a href="http://login.spotlite.com.au/log/crawler_activity">
                                <i class="fa fa-gear"></i>
                                <span>Crawler Logs</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="http://login.spotlite.com.au/log/user_activity">
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
                            <a href="http://login.spotlite.com.au/term_and_condition">
                                <i class="fa fa-square"></i>
                                <span>Terms and Conditions</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="http://login.spotlite.com.au/privacy_policy">
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
</style>