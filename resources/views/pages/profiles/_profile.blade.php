@php
    /**
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var boolean $ownership
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $friendshipRequestForm
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $quickEditImagesForm
     */
@endphp

@extends('layouts.default')

@section('main')
    <header>
        @include('partials.navbar.navbar',['profile' => $authProfile])
    </header>

    <aside>
        @include('partials.sidebar.sidebar',['profile' => $authProfile])
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">

        <section>
            @include('partials.profile.images-banner', [
                'quickEditImagesForm' => $quickEditImagesForm,
                'profile' => $profile,
                'ownership' => $ownership,
                ])
        </section>

        <section>
            @include('partials.profile.friendships', [
                'friendshipRequestForm' => $friendshipRequestForm,
                'profile' => $profile,
                'authProfile' => $authProfile,
                'ownership' => $ownership,
                ])
        </section>

        <section>
            @include('partials.profile.info', ['profile' => $profile])
        </section>


        @for($i=0;$i<20;$i++)
            <section class="mt-5 flex items-center justify-center text-3xl border-8 aspect-[3/1]">
                Post
            </section>
        @endfor

    </main>

@endsection
