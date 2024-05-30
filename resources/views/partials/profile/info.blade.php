@php
    /**
     * @var \App\Models\Profile $profile
     */
@endphp

<div>
    @if($profile->bio)
        <div class="mt-2 flex flex-col justify-end w-full lg:text-2xl">
            <span class="opacity-60">Bio</span>
            <span>{{$profile->bio}}</span>
        </div>
    @endif
</div>
