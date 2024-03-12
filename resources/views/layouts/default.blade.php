@include('stacks.favicon')
@include('stacks.assets')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    @stack('head')
</head>

<body class="flex min-h-screen flex-col bg-white">
    @stack('body-start')

    @yield('main')

    @stack('body-end')
</body>

</html>
