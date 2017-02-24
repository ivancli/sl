<!doctype html>
<html lang="en">
<head>
    {{--redirect if js not available--}}
    <noscript>
        <meta http-equiv="refresh" content="0; url={{route('errors.javascript_disabled')}}"/>
    </noscript>
    {{--redirect if cookie not available, unable to store login session anyway without cookie--}}
    <script type="text/javascript">
        if (navigator.cookieEnabled == false) {
            window.location = "{{route('errors.cookie_disabled')}}";
        }
    </script>

    @component('components.csrf_token_meta')
    @endcomponent
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/images/favicon.png')}}"/>
    <title>@yield('title', 'SpotLite')</title>
    @yield('links')
    @yield('head_scripts')
    @component('components.csrf_token_script')
    @endcomponent
    @component('components.variable_setter')
    @endcomponent
</head>
<body class="skin-black-light">
@yield('body')
@yield('scripts')
</body>
</html>