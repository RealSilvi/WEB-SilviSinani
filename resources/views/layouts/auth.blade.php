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

    <div class="flex min-h-screen flex-col">

        <main class="flex flex-1 flex-col text-primary">
            @yield('main')
        </main>

        <footer>
            <div class="flex h-10 items-center justify-center bg-primary/20 text-xs text-primary">
                {{ env('APP_NAME') }}
            </div>
        </footer>
    </div>

    @stack('body-end')
</body>

</html>
