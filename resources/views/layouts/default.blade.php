{{--@include('stacks.seo')--}}
@include('stacks.favicon')
@include('stacks.assets')
{{--@include('stacks.maps')--}}
{{--@include('stacks.tagmanager')--}}
{{--@include('stacks.cookie')--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    @stack('head')
</head>

<body class="bg-white font-sans antialiased">
@stack('body-start')

@yield('main')

{{--@include('partials.polyglot')--}}
{{--@include('partials.toast')--}}
{{--@include('partials.whatsapp-button')--}}

@stack('body-end')
</body>

</html>

