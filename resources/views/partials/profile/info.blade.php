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
<div>
    @if($profile->bio)
        <div class="mt-2 flex flex-col justify-end w-full lg:text-2xl gap-1 lg:gap-5">
            <span class="opacity-60">{{__('pages.profile.bio')}}</span>
            <span
                x-data="{showText:false}"
                @click="showText=true"
                class="cursor-pointer"
                :class="showText?'line-clamp-none':'line-clamp-2'">
                {{$profile->bio}}
            </span>
        </div>
    @endif
</div>
