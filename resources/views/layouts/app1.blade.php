<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    @include('layouts.head')

</head>

<body class="hold-transition layout-navbar-fixed layout-fixed">
    <div class="wrapper">

        @include('layouts.navbar1')
        @include('layouts.sidebar1')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper mi-bg">

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            @if (Session::has('success'))                            
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p> {{ Session::get('success') }} </p>                               
                              </div>
                            @endif

                            @if (Session::has('error'))                            
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p> {{ Session::get('error') }} </p>                               
                              </div>
                            @endif    
                        </div>
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page_title')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            @yield('bread_crumb')
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">

                        @yield('main_content')

                    </div><!-- /.main-row -->
                </div><!-- /.container-fluid -->
            </div><!-- /.content -->            
        </div> <!-- ./content wrapper -->
        @include('layouts.footer')
    </div> <!-- ./wrapper -->
        @include('layouts.foot')
</body>

</html>