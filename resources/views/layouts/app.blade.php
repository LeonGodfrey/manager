<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    @include('layouts.head')

</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed mi-bg">
    <div class="wrapper">

        @include('layouts.navbar')
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <section class="content-header mr-2">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <p> {{ Session::get('success') }} </p>
                                </div>
                            @endif

                            @if (Session::has('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
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
            </section>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content mr-2">
                <div class="container-fluid">
                    <div class="row">

                        @yield('main_content')

                    </div><!-- /.main-row -->
                </div><!-- /.container-fluid -->
            </section><!-- /.content -->
        </div> <!-- ./content wrapper -->
        @include('layouts.footer')
    </div> <!-- ./wrapper -->
    @include('layouts.foot')
</body>

</html>
