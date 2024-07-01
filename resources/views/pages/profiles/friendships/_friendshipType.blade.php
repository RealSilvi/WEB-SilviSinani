@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var boolean $ownership
     * @var boolean $followers
     */


    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;
    $ownership = $ownership ?? false;
    $followers = $followers ?? false;
@endphp

@extends('layouts.default',[
    'title'=>__('pages.profile.friendships.title')
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

    <main x-data="friendshipsContext({
            userId: {{$user->id}},
            profileId: {{$profile->id}},
            authProfileId: {{$authProfile->id}},
            authProfileNickname: '{{$authProfile->nickname}}',
            context:`{{ $followers ? 'FOLLOWERS' : 'FOLLOWING' }}`,
             })"
          @delete-friend.window="onFriendInteracted($event)"
          class="mx-auto w-full max-w-screen-2xl flex-1 flex flex-col pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">

        <section x-show="friends.length > 0"
                 class="mt-5">
            <div class="flex flex-col lg:flex-row w-full justify-center lg:flex-wrap lg:mt-10">
                <template x-for="friend in friends">
                    <div class="lg:w-1/3 lg:p-5">
                        <div class="h-full w-full border-b-4 py-5 lg:rounded-full lg:border-4 lg:p-2 lg:hover:bg-primary/50">
                            <div class="grid grid-cols-3 items-center justify-center gap-6 rounded-full p-1 hover:bg-primary/50 lg:hover:bg-transparent">
                                <a :href="friend.profileLink">
                                    <img :src="friend.mainImage"
                                         :alt="friend.nickname"
                                         class="aspect-[1/1] rounded-full object-cover">
                                </a>
                                <div class="flex flex-col gap-5">
                                    <span class="opacity-60" x-text="friend.type"></span>
                                    <a :href="friend.profileLink" x-text="`@${friend.nickname}`">
                                    </a>
                                </div>
                                <div class="grid h-full grid-cols-2 mb-2">
                                    <div></div>

                                    <button @click="deleteFriend(friend.id)"
                                            class="h-full w-full items-center justify-center">
                                        {{ svg('decline', 'h-8 w-8 text-error/75') }}
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="!lastFriendsPage"
                 x-intersect="loadMore()"
                 class="w-full text-center italic">
                {{__('pages.profile.loading')}}
            </div>
        </section>

        <section x-show="friends.length === 0" class="flex-1">
            <div class="w-full h-full flex items-center justify-center text-center text-2xl font-medium ">
                <div class="flex flex-col lg:flex-row items-center justify-center gap-5">
                    <span>
                        {{__('pages.profile.friendships.no_result')}}
                    </span>
                    <a href="{{route('dashboard',['profile'=>$profile->nickname])}}">{{svg('other-logo','h-8 w-8 lg:h-10 lg:w-10')}}</a>
                </div>
            </div>
        </section>

    </main>
@endsection
