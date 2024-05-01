@php
    /**
     * @var \App\Models\Profile $profile
     * @var \App\Models\User $user
     */
//    dd($profile)
@endphp

@extends('layouts.default')

@section('main')
    <header>
        @include('partials.navbar.navbar')
    </header>

    <aside>
        @include('partials.sidebar.sidebar')
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1">
        <section class="mt-5">
            <img src="{{$profile->secondary_image}}" alt="{{$profile->nickname.' secondary image'}}"
                 class="object-cover aspect-[7/3] w-full rounded-xl" />

            <div class="-mt-5 lg:relative  px-2">
                <div class="grid grid-cols-3 justify-around items-center gap-5 lg:absolute lg:-translate-y-2/3">
                    <img src="{{$profile->main_image}}" alt="{{$profile->nickname.' main image'}}"
                         class="block aspect-[1/1] w-full rounded-full object-cover" />

                    <div
                        class="flex flex-col justify-end h-full lg:order-first lg:flex-row lg:gap-10 lg:p-3 lg:items-end lg:justify-center lg:text-2xl">
                        <span class="opacity-60">{{$profile->type}}</span>
                        <span>{{'@'.$profile->nickname}}</span>
                    </div>

                    <div class="flex items-end justify-center h-full">
                        <button class="bg-primary/10 lg:text-2xl rounded-xl py-2 w-full lg:p-3 lg:w-2/3 h-fit">
                            Follow
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex flex-col justify-end h-full lg:mt-40 lg:text-2xl">
                <span class="opacity-60">Bio</span>
                <span>{{$profile->bio}}</span>
            </div>

        </section>
        @for($i=0;$i<20;$i++)
            <section class="mt-5 flex items-center justify-center text-3xl border-8 aspect-[3/1]">
                Post
            </section>
        @endfor

    </main>

@endsection
