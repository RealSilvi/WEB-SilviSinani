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

<div class="h-full w-full py-5 lg:rounded-full lg:p-2">
    <a class="grid grid-cols-3 items-center justify-center gap-10 rounded-full p-1 bg-white"
       :href="profile.profileLink">
        <div>
            <img :src="profile.mainImage" :alt="`${profile.nickname} profile image.`"
                 class="aspect-[1/1] rounded-full object-cover">
        </div>

        <div class="col-span-2 flex flex-col">
            <span class="opacity-60" x-text="profile.type"></span>
            <span x-text="`@${profile.nickname}`"></span>
        </div>
    </a>
</div>
