@php
    /**
     * @var \Illuminate\Database\Eloquent\Collection<array-key,\App\Models\Profile> $profiles
     */
@endphp

@extends('layouts.default', [
    'title' => __('Search results'),
])

@section('main')
    <header>
        @include('partials.navbar.navbar')
    </header>

    <aside>
        @include('partials.sidebar.sidebar')
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">

        @if($profiles->isEmpty())
            <section class="w-full h-full flex items-center justify-center text-center text-2xl font-medium ">
                <div class="flex flex-col lg:flex-row items-center justify-center gap-5">
                    <span>
                        Non sono stati trovati profili con il nickname indicato
                    </span>
                    <a href="{{route('dashboard',['profile'=>$profile->nickname])}}">{{svg('other-logo','h-8 w-8 lg:h-10 lg:w-10')}}</a>
                </div>
            </section>
        @endif

        <section class="mt-10 px-18 lg:px-5 xl:mt-20 xl:px-21">
            <div
                class="flex flex-col items-center justify-center gap-11 lg:grid lg:grid-cols-4 lg:items-start lg:gap-x-6 xl:gap-y-20">
                @foreach ($profiles as $profile)
                    {{$profile->nickname }}
                @endforeach
            </div>
        </section>

    </main>

@endsection
