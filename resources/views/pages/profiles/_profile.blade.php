@php
    /**
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var \App\Models\User $user
     * @var boolean $ownership
     * @var \App\Enum\FriendshipStatus $friendshipStatus
     */

    $redirectUrl= route('profile',['user'=>$user->id,'profile'=>$authProfile->nickname]);
    $editFormId = 'edit_image_profile_form';
    $editMethod = 'PATCH';
    $editActionUrl = route('users.profiles.update',['user'=>$user->id,'profile'=>$authProfile->id]);

    $followFormId = 'follow_request_form';
    $followMethod = 'POST';
    $followActionUrl = route('users.profiles.following.store',['user'=>$user->id,'profile'=>$authProfile->id]);

    $unfollowFormId = 'unfollow_request_form';
    $unfollowMethod = 'DELETE';
    $unfollowActionUrl = route('users.profiles.following.destroy',['user'=>$user->id,'profile'=>$authProfile->id,'following'=>$profile->id]);
@endphp

@extends('layouts.default')

@section('main')
    <header>
        @include('partials.navbar.navbar',['profile'=>$authProfile])
    </header>

    <aside>
        @include('partials.sidebar.sidebar',['profile'=>$authProfile])
    </aside>

    <main
            x-cloak
            x-data="{showEditBackgroundImageModal:false,showEditProfileImageModal:false}"
            x-effect="document.body.style.overflow = showEditBackgroundImageModal || showEditProfileImageModal ? 'hidden' : ''"
            class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">
        <section>
            <button
                    @click="showEditBackgroundImageModal = true"
                    class="w-full">
                <img src="{{asset($profile->secondary_image)}}" alt="{{$profile->nickname.' secondary image'}}"
                     class="object-cover aspect-[7/3] w-full rounded-xl" />
            </button>

            <div class="-mt-5 lg:relative px-2 ">
                <div
                        class="grid grid-cols-3 w-full justify-around items-center gap-5 lg:absolute lg:-translate-y-2/3 ">
                    <button
                            @click="showEditProfileImageModal = true">
                        <img src="{{asset($profile->main_image)}}" alt="{{$profile->nickname.' main image'}}"
                             class="block aspect-[1/1] w-full rounded-full object-cover" />
                    </button>

                    <div
                            class="flex flex-col justify-end h-full lg:order-first lg:flex-row lg:gap-10 lg:p-3 lg:items-end lg:justify-center lg:text-2xl">
                        <span class="opacity-60">{{$profile->type}}</span>
                        <span>{{'@'.$profile->nickname}}</span>
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="mt-5 flex flex-row gap-2 lg:gap-10 justify-between items-center h-full lg:mt-40 lg:text-2xl">
                <a href="{{route('friendships',['profile'=>$profile->nickname,'friendshipType'=>\App\Enum\FriendshipType::FOLLOWER->value,'authProfile'=>$authProfile->nickname])}}"
                   class="bg-primary/10 lg:text-2xl rounded-xl py-2 w-full lg:p-3 lg:w-2/3 h-fit text-center lg:text-start">
                    Followers <br class="lg:hidden"> {{$profile->followers_count}}
                </a>
                @if(!$ownership)
                    @if($friendshipStatus == \App\Enum\FriendshipStatus::NONE)
                        <form action="{{$followActionUrl}}"
                              method="{{ $followMethod }}"
                              x-data="formSubmit({
                            formId: '{{ $followFormId }}',
                            url: '{{ $followActionUrl }}',
                            method: '{{ $followMethod }}',
                            onSuccessRedirectUrl: '{{ $redirectUrl }}',
                          })"
                              @submit.prevent="submit"
                              class="w-full h-full">
                            @csrf
                            <x-form.group name="followerId">
                                <x-form.input type="hidden" value="{{$profile->id}}"></x-form.input>
                            </x-form.group>

                            <x-form.submit
                                    class="bg-primary/10 font-normal lg:text-2xl rounded-xl py-2 w-full lg:p-3 h-full lg:h-fit !text-primary">
                                Follow
                            </x-form.submit>
                        </form>
                    @else
                        <form action="{{$unfollowActionUrl}}"
                              method="{{ $unfollowMethod }}"
                              x-data="formSubmit({
                            formId: '{{ $unfollowFormId }}',
                            url: '{{ $unfollowActionUrl }}',
                            method: '{{ $unfollowMethod }}',
                            onSuccessRedirectUrl: '{{ $redirectUrl }}',
                          })"
                              @submit.prevent="submit"
                              class="w-full">
                            @csrf
                            <x-form.submit
                                    class="bg-primary/10 font-normal lg:text-2xl rounded-xl py-2 w-full h-full lg:p-3 lg:h-fit !text-primary">
                                {{$friendshipStatus == \App\Enum\FriendshipStatus::WAITING?'Waiting':'Unfollow'}}
                            </x-form.submit>
                        </form>
                    @endif
                @endif
                <a href="{{route('friendships',['profile'=>$profile->nickname,'friendshipType'=>\App\Enum\FriendshipType::FOLLOWING->value,'authProfile'=>$authProfile->nickname])}}"
                    class="bg-primary/10 lg:text-2xl rounded-xl py-2 w-full lg:p-3 lg:w-2/3 h-fit text-center lg:text-start">
                    Following <br class="lg:hidden"> {{$profile->following_count}}
                </a>
            </div>

            @if($profile->bio)
                <div class="mt-2 flex flex-col justify-end w-full lg:text-2xl">
                    <span class="opacity-60">Bio</span>
                    <span>as{{$profile->bio}}</span>
                </div>
            @endif
        </section>

        <section x-show="showEditBackgroundImageModal"
                 class="fixed h-full w-full bg-black/50 z-50 inset-0 px-5 pb-16 pt-20 lg:pb-5 lg:pr-20">
            <div
                    class="relative h-full w-full bg-black/50 rounded-xl inset-0 flex flex-col items-center gap-2 justify-center py-10 px-5 lg:p-12">
                <button class="absolute right-2 top-2 lg:right-5 lg:top-5" @click="showEditBackgroundImageModal=false">
                    {{svg('close','h-5 w-5 lg:h-7 lg:w-7 text-white')}}
                </button>

                <div
                        class="flex items-center justify-center h-5/6">
                    <img src="{{asset($profile->secondary_image)}}" alt="{{$profile->nickname.' secondary image'}}"
                         class="object-contain w-full h-full rounded-xl" />
                </div>
                @if($ownership)
                    <div class="w-full h-16 lg:h-12">
                        <form action="{{$editActionUrl}}"
                              method="PATCH"
                              x-data="formSubmit({
                            formId: '{{ $editFormId }}',
                            url: '{{ $editActionUrl }}',
                            method: '{{ $editMethod }}',
                            onSuccessRedirectUrl: '{{ $redirectUrl }}',
                          })"
                              @submit.prevent="submit"
                              class=" h-full w-full flex flex-col items-center justify-between gap-2">
                            @csrf
                            <x-form.group name="secondaryImage" class=" rounded-xl bg-primary px-3 w-fit">
                                <x-form.label sr-only>
                                    {{ __('form.profile_edit.secondary_image') }}
                                </x-form.label>

                                <x-form.image-picker class="h-20 lg:text-xl text-white" />
                            </x-form.group>

                            <x-form.submit class="rounded-full font-black lg:font-medium px-10 py-2">
                                {{ __('form.profile_edit.submit_button') }}
                            </x-form.submit>
                        </form>
                    </div>
                @endif
            </div>
        </section>
        <section x-show="showEditProfileImageModal"
                 class="fixed h-full w-full bg-black/50 z-50 inset-0 px-5 pb-16 pt-20 lg:pb-5 lg:pr-20">
            <div
                    class="relative h-full w-full bg-black/50 rounded-xl inset-0 flex flex-col items-center gap-2 justify-center py-10 px-5 lg:p-12">
                <button class="absolute right-2 top-2 lg:right-5 lg:top-5" @click="showEditProfileImageModal=false">
                    {{svg('close','h-5 w-5 lg:h-7 lg:w-7 text-white')}}
                </button>

                <div
                        class="flex items-center justify-center h-5/6">
                    <img src="{{asset($profile->main_image)}}" alt="{{$profile->nickname.' secondary image'}}"
                         class="object-contain w-full h-full rounded-xl" />
                </div>
                @if($ownership)
                    <div class="w-full h-16 lg:h-12">
                        <form action="{{$editActionUrl}}"
                              method="PATCH"
                              x-data="formSubmit({
                            formId: '{{ $editFormId }}',
                            url: '{{ $editActionUrl }}',
                            method: '{{ $editMethod }}',
                            onSuccessRedirectUrl: '{{ $redirectUrl }}',
                          })"
                              @submit.prevent="submit"
                              class=" h-full w-full flex flex-col items-center justify-between gap-2">
                            @csrf
                            <x-form.group name="mainImage" class=" rounded-xl bg-primary px-3 w-fit">
                                <x-form.label sr-only>
                                    {{ __('form.profile_edit.secondary_image') }}
                                </x-form.label>

                                <x-form.image-picker class="h-20 lg:text-xl text-white" />
                            </x-form.group>

                            <x-form.submit class="rounded-full font-black lg:font-medium px-10 py-2">
                                {{ __('form.profile_edit.submit_button') }}
                            </x-form.submit>
                        </form>
                    </div>
                @endif
            </div>
        </section>
        @for($i=0;$i<20;$i++)
            <section class="mt-5 flex items-center justify-center text-3xl border-8 aspect-[3/1]">
                Post
            </section>
        @endfor

    </main>

@endsection
