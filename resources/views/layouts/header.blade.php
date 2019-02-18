<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>NDPS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> 
    @include('layouts.css_links')

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
        
            <!-- Logo -->
            <a href="entry_form" class="logo">           
            <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>NDPS</b>
            </a>
        </span>
            </a>
            
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{asset('images/FacelessMan.png')}}" class="user-image" alt="User Image">
                                <span class="hidden-xs">&nbsp; </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{asset('images/FacelessMan.png')}}" class="img-circle" alt="User Image">
                                    <p>
                                        &nbsp;
                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="update_password" class="btn btn-primary btn-flat">Update Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#"  class="btn btn-danger btn-flat"  onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">Sign out</a>
                                        <form id="logout-form" action="#" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->

                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                        <li class="treeview">
                            <a href="#"><i class="fa fa-search-minus"></i>
                                <span>Reports/Enquiry</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="monthly_report">Monthly Report</a></li>
                                <li><a href="previous_report_view">Previous Report</a></li>
                            </ul>
                        </li>
                    
                        <li class="header"></li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-edit"></i>
                                <span>Master Maintainance</span>
                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>District Master Maintainance</span></a></li>
                                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Court Master Maintainance</span></a></li>
                                <li><a href="stakeholder_view"><i class="fa fa-circle-o text-violate"></i> <span>Stakeholder Maintainance</span></a></li>
                                <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Magistrate Master Maintainance</span></a></li>
                                <li><a href="#"><i class="fa fa-circle-o text-green"></i> <span>Storage Master Maintainance</span></a></li>                                
                                <li><a href="#"><i class="fa fa-circle-o text-purple"></i> <span>Drug Master Maintainance</span></a></li>
                            </ul>
                        </li>

                        <li class="header"></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-user"></i>
                                <span>User Maintainance</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#">User Creation</a></li>
                                <li><a href="#">Block User</a></li>

                            </ul>
                        </li>
                   
                   

                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.js_links')