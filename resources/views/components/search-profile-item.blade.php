@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var \App\Models\Profile $searchProfile
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;
@endphp

<div class="h-full w-full border-b-4 py-5 lg:rounded-full lg:border-4 lg:p-2 lg:hover:bg-primary/50">
    <a class="grid grid-cols-3 items-center justify-center gap-10 rounded-full p-1 hover:bg-primary/50 lg:hover:bg-transparent"
        href="{{ route('profile', [
            'profile' => $searchProfile->nickname,
            'authProfile' => $authProfile->nickname,
        ]) }}">
        <div>
            <img src="{{ asset($searchProfile->main_image) }}" alt="{{ $searchProfile->nickname . ' ' . 'main image' }}"
                class="aspect-[1/1] rounded-full object-cover">
        </div>

        <div class="col-span-2 flex flex-col">
            <span class="opacity-60">{{ $searchProfile->type }}</span>
            <span>{{ '@' . $searchProfile->nickname }}</span>
        </div>
    </a>
</div>
