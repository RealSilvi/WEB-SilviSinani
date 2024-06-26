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

<div class="mt-5 flex flex-row gap-2 lg:gap-10 justify-between items-center h-full lg:mt-40 lg:text-2xl">

    <a class="bg-primary/10 lg:text-2xl rounded-xl py-2 w-full lg:p-3 lg:w-2/3 h-fit text-center"
       href="{{ route('friendships',[
            'profile'=>$profile->nickname,
            'friendshipType'=>\App\Enum\FriendshipType::FOLLOWER->value,
            'authProfile'=>$authProfile->nickname]) }}">
        {{__('pages.profile.followers')}} <br class="lg:hidden"> {{ $profile->followers_count }}
    </a>

    {{--    @if(!$ownership)--}}
    {{--        <form class="w-full h-full"--}}
    {{--            x-data="formSubmit({--}}
    {{--                  formId: '{{ $friendshipRequestForm['id'] }}',--}}
    {{--                  url: '{{ $friendshipRequestForm['action'] }}',--}}
    {{--                  method: '{{ $friendshipRequestForm['method'] }}',--}}
    {{--                  onSuccessMessage: '{{ $friendshipRequestForm['onSuccessMessage'] }}',--}}
    {{--                  onFailMessage: '{{ $friendshipRequestForm['onFailMessage'] }}',--}}
    {{--                  })"--}}
    {{--              @submit.prevent="submit">--}}
    {{--            @csrf--}}
    {{--            <x-form.group name="followerId">--}}
    {{--                <x-form.input type="hidden" value="{{ $profile->id }}"></x-form.input>--}}
    {{--            </x-form.group>--}}

    {{--            <x-form.submit--}}
    {{--                    class="bg-primary/10 font-normal lg:text-2xl rounded-xl py-2 w-full lg:p-3 h-full lg:h-fit !text-primary">--}}
    {{--                {{ $friendshipRequestForm['submitLabel'] }}--}}
    {{--            </x-form.submit>--}}
    {{--        </form>--}}
    {{--    @endif--}}

    @if(!$ownership)
        <div class="w-full h-full"
             x-data="profileFollowing({
                userId: {{$user->id}},
                profileId: {{$authProfile->id}},
                followerId: {{$profile->id}}
             })"
             x-init="loadFriendshipStatus"  >
            <div x-text="friendshipStatus"
                 @click="triggerFollowingRequest"
                class="flex cursor-pointer items-center justify-center bg-primary/10 font-normal lg:text-2xl rounded-xl py-2 w-full lg:p-3 h-full lg:h-fit !text-primary">
            </div>
        </div>
    @endif

    <a class="bg-primary/10 lg:text-2xl rounded-xl py-2 w-full lg:p-3 lg:w-2/3 h-fit text-center"
       href="{{ route('friendships', [
            'profile' => $profile->nickname,
            'friendshipType' => \App\Enum\FriendshipType::FOLLOWING->value,
            'authProfile' => $authProfile->nickname]) }}">
        {{__('pages.profile.following')}} <br class="lg:hidden"> {{ $profile->following_count }}
    </a>
</div>
