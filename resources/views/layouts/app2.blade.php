<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    @include('layouts.head')

</head>

<body class="hold-transition login-page mi-bg">
    <div class="login-box">
        @yield('main_content')
    </div>
    @include('layouts.foot')
</body>

</html>
