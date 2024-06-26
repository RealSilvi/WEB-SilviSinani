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

<div
    x-data="profileFollowers({
                userId: {{$user->id}},
                profileId: {{$authProfile->id}},
                followerId: followRequest.from.id
            })"
    class="h-full w-full border-b-4 py-5 lg:rounded-full lg:border-4 lg:p-2 lg:hover:bg-primary/50">
    <div
        class="grid grid-cols-3 items-center justify-center gap-6 rounded-full p-1 hover:bg-primary/50 lg:hover:bg-transparent">
        <a href="#">
            <img :src="followRequest.from.mainImage"
                 :alt="followRequest.from.nickname"
                 class="aspect-[1/1] rounded-full object-cover">
        </a>
        <div class="flex flex-col gap-5">
            <span class="opacity-60" x-text="followRequest.from.type"></span>
            <a href="#" x-text="`@${followRequest.from.nickname}`">
            </a>
        </div>
        <div class="grid h-full grid-cols-2">
            <button @click="acceptFollowRequest"
                    class="h-full w-full items-center justify-center">
                {{ svg('accept', 'h-8 w-8 text-success/75') }}
            </button>

            <button @click="deleteFollowRequest"
                    class="h-full w-full items-center justify-center">
                {{ svg('decline', 'h-8 w-8 text-error/75') }}
            </button>
        </div>

    </div>
</div>
