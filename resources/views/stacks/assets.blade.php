@prepend('head')
@endprepend

@push('head')
    @livewireStyles
    @vite(['resources/frontend/css/app.css', 'resources/frontend/js/app.ts'])
@endpush

@push('body-end')
    @livewireScriptConfig
@endpush
