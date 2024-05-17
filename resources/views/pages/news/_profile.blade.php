@php
    /**
     * @var \App\Models\Profile $profile
     * @var \App\Models\User $user
     * @var \Illuminate\Database\Eloquent\Collection<array-key,\App\Models\Profile> $pendingFollowers
     */
@endphp

@extends('layouts.default')

@section('main')
    <header>
        @include('partials.navbar.navbar')
    </header>

    <aside>
        @include('partials.sidebar.sidebar')
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">
        @if($pendingFollowers->isEmpty())
            <section class="w-full h-full flex items-center justify-center text-center text-2xl font-medium ">
                <div class="flex flex-col lg:flex-row items-center justify-center gap-5">
                    <span>
                        Non hai richieste
                    </span>
                    <a href="{{route('dashboard',['profile'=>$profile->nickname])}}">{{svg('other-logo','h-8 w-8 lg:h-10 lg:w-10')}}</a>
                </div>
            </section>
        @endif

        <section class="flex flex-col lg:flex-row w-full justify-center lg:flex-wrap">
            @foreach ($pendingFollowers as $followRequest)
                <div class="lg:w-1/3 lg:p-5">
                    <x-news-follow-request :authProfile="$profile" :followRequestProfile="$followRequest"></x-news-follow-request>
                </div>
            @endforeach
        </section>
    </main>

@endsection
