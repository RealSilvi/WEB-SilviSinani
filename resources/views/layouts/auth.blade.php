@extends('layouts.default')

@section('main')
    <div class="flex min-h-screen flex-col">

        <main class="flex flex-1 flex-col text-primary">
            @yield('content')
        </main>

        <footer>
            <div class="flex h-10 items-center justify-center bg-primary/20 text-xs text-primary">
                {{ env('APP_NAME') }}
            </div>
        </footer>
    </div>
@endsection
