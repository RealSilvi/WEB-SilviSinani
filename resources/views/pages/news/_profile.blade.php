@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;
@endphp

@extends('layouts.default', [
    'title' => __('pages.news.title'),
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

    <main x-data="newsContext({
                 userId: {{$user->id}},
                 profileId: {{$profile->id}},
                 authProfileId: {{$authProfile->id}},
                 onSuccessMessage:'{{__('messages.load_news.on_success')}}',
                 onFailMessage:'{{__('messages.load_news.on_fail')}}'
             })"
          @follower-accepted.window="onFollowerRequestInteracted($event)"
          @delete-follower.window="onFollowerRequestInteracted($event)"
          class="mx-auto w-full max-w-screen-2xl flex flex-col flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">

        <section x-show="followRequests.length > 0"
                 x-data="{showAllFollowRequests:false}"
                 class="mt-5">
            <div class="flex flex-col">
                <template x-if="!lastFollowRequestsPage">
                    <button @click="showAllFollowRequests=true"
                            :class="showAllFollowRequests?'hidden':'block'"
                            class="font-black text-primary text-base block text-end">
                        {{__('pages.news.see_all')}}
                    </button>
                </template>
                <span class="font-medium text-4xl block text-center">
                    {{__('pages.news.follow_requests_news')}}
                </span>
            </div>

            <div class="flex flex-col lg:flex-row w-full justify-center lg:flex-wrap">
                <template x-for="followRequest in followRequests">
                    <div class="lg:w-1/3 lg:p-5">
                        @include('partials.news.follow-request', [
                                                    'user' => $user,
                                                    'authProfile' => $authProfile,
                                                    'profile' => $profile,
                                                ])
                    </div>
                </template>
            </div>

            <div x-show="showAllFollowRequests && !lastFollowRequestsPage" x-intersect="loadMoreFollowRequests()"
                 class="w-full text-center italic">
                {{__('pages.profile.loading')}}
            </div>
        </section>

        <section x-show="generalNews.length > 0"
                 x-data="{showAllGeneralNews:false}"
                 class="mt-10">

            <div class="flex flex-col">
                <template x-if="!lastGeneralNewsPage">
                    <button @click="showAllGeneralNews=true"
                            :class="showAllGeneralNews?'hidden':'block'"
                            class="font-black text-primary text-base block text-end">
                        {{__('pages.news.see_all')}}
                    </button>
                </template>
                <span class="font-medium text-4xl block text-center">
                    {{__('pages.news.general_news')}}
                </span>
            </div>

            <div class="mt-5 flex flex-col gap-5 lg:flex-row w-full justify-center lg:flex-wrap">
                <template x-for="news in generalNews">
                    <div class="w-full cursor-pointer hover:bg-primary/50 p-2 rounded-full">
                        <span x-text="news.title"></span>
                    </div>
                </template>
            </div>

            <div x-show="showAllGeneralNews && !lastGeneralNewsPage" x-intersect="loadMoreGeneralNews()"
                 class="w-full text-center italic">
                {{__('pages.profile.loading')}}
            </div>
        </section>

        <section x-show="followRequests.length === 0 && generalNews.length === 0" class="flex-1">
            <div class="w-full h-full flex items-center justify-center text-center text-2xl font-medium ">
                <div class="flex flex-col lg:flex-row items-center justify-center gap-5">
                    <span>
                        {{__('pages.news.no_results')}}
                    </span>
                    <a href="{{route('dashboard',['profile'=>$profile->nickname])}}">{{svg('other-logo','h-8 w-8 lg:h-10 lg:w-10')}}</a>
                </div>
            </div>
        </section>

    </main>

@endsection
