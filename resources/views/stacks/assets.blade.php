@prepend('head')
    <link rel="preconnect" href="https://wsrv.nl">
    <link rel="preload" href="{{ Vite::asset('resources/frontend/fonts/MavenPro-VariableFont_wght.woff2') }}" as="font"
          crossorigin>
@endprepend

@push('head')
    @vite(['resources/frontend/css/app.css', 'resources/frontend/js/app.ts'])
@endpush
