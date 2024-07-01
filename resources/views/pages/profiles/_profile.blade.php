@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var boolean $ownership
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;
    $ownership = $ownership ?? false;
@endphp

@extends('layouts.default',[
    'title'=>__('pages.profiles.title')
])

@section('main')
    <header>
        @include('partials.navbar.navbar', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
        ])
    </header>

    <aside>
        @include('partials.sidebar.sidebar', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
        ])
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">

        <section>
            @include('partials.profile.images-banner', [
                'user' => $user,
                'profile' => $profile,
                'authProfile' => $authProfile,
                'ownership' => $ownership,
                ])
        </section>

        <section>
            @include('partials.profile.friendships', [
                'user' => $user,
                'authProfile' => $authProfile,
                'profile' => $profile,
                'ownership' => $ownership,
                ])
        </section>

        @if($ownership)
            <section class="mt-10 lg:mt-14">
                @include('partials.posts.new-post', [
                    'user' => $user,
                    'authProfile' => $authProfile,
                    'profile' => $profile,
                ])
            </section>
        @endif

        <section class="mt-10 lg:mt-14">
            @include('partials.profile.info', [
                    'user' => $user,
                    'authProfile' => $authProfile,
                    'profile' => $profile,
                    'ownership'=>$ownership
                ])
        </section>

        <section class="mt-10 lg:mt-14">
            @include('partials.posts.list', [
                'user' => $user,
                'authProfile' => $authProfile,
                'profile' => $profile,
                'ownership' => $ownership,
                'context' => 'PROFILE',
                ])
        </section>

    </main>

@endsection
