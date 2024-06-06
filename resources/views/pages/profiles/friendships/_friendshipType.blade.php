@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var boolean $ownership
     * @var \Illuminate\Database\Eloquent\Collection<array-key,\App\Models\Profile> $friendships
     * @var \App\Enum\FriendshipType $friendshipType
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;
    $ownership = $ownership ?? false;
@endphp

@extends('layouts.default')

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
        @if($friendships->isEmpty())

            <section class="w-full h-full flex items-center justify-center text-center text-2xl font-medium ">
                <div class="flex flex-col lg:flex-row items-center justify-center gap-5">
                    <span>
                        Non hai richieste
                    </span>
                    <a href="{{route('dashboard',['profile'=>$authProfile->nickname])}}">{{svg('other-logo','h-8 w-8 lg:h-10 lg:w-10')}}</a>
                </div>
            </section>
        @else
            @if($ownership)
                <section class="flex flex-col lg:flex-row w-full justify-center lg:flex-wrap">
                    @foreach ($friendships as $friendProfile)
                        <div class="lg:w-1/3 lg:p-5">
                            <x-edit-friend :authProfile="$authProfile" :friendProfile="$friendProfile"
                                           :friendshipType="$friendshipType" :onlyDelete="true"></x-edit-friend>
                        </div>
                    @endforeach

                </section>
            @else
                <section class="flex flex-col lg:flex-row w-full justify-center lg:flex-wrap">
                    @foreach ($friendships as $searchProfile)
                        <div class="lg:w-1/3 lg:p-5">
                            <x-search-profile-item :authProfile="$authProfile"
                                                   :searchProfile="$searchProfile"></x-search-profile-item>
                        </div>
                    @endforeach
                </section>
            @endif
        @endif
    </main>
@endsection
