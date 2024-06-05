@php
    /**
     * @var \App\Models\Profile $profile
     * @var \App\Models\User $user
     */
    $user= $user ?? auth()->user();
    $profile= $profile ?? $user->getDefaultProfile();
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
        User: {{auth()->user()}}<br><br>
        Profile: {{$profile}}
    </main>

@endsection
