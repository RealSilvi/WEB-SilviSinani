@extends('layouts.default')

@section('main')
    <header>
        @include('partials.navbar.navbar')
    </header>

    <aside>
        @include('partials.sidebar.sidebar')
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1 overflow-x-hidden">
        User: {{auth()->user()}}<br><br>
        Profile: {{$profile}}
    </main>

@endsection
