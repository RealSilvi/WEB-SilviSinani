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

    <div class="flex flex-1 overflow-hidden px-2 pb-15 lg:pb-2 lg:pr-20">
        <div class="mt-20 flex flex-1 flex-col overflow-x-hidden rounded-xl bg-primary/10 p-5 lg:p-10">
            @yield('main')
        </div>
    </div>

    @stack('body-end')
</body>

</html>
