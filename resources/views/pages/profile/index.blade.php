@extends('layouts.default')

@section('main')
    <header>
        @include('partials.navbar.navbar')
    </header>

    <main class="flex-1 w-full max-w-screen-2xl mx-auto ">
        Main
    </main>

    <footer>
        @include('partials.footer.footer')
    </footer>
@endsection
