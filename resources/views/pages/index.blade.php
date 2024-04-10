@extends('layouts.default')

@section('main')
    <header>
        @include('partials.navbar.navbar')
    </header>

    <aside>
        @include('partials.sidebar.sidebar')
    </aside>

    <main class="mx-auto flex h-full w-full max-w-screen-2xl flex-1 flex-col px-2 pb-15 lg:pb-2 lg:pr-20">
        <div class="flex-1 rounded-xl bg-primary/10">

        </div>
    </main>

    <footer>
        {{--        @include('partials.footer.footer') --}}
    </footer>
@endsection
