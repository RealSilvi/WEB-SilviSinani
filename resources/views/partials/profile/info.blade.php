@php
    /**
     * @var \App\Models\Profile $profile
     */
@endphp

<div>
    @if($profile->bio)
        <div class="mt-2 flex flex-col justify-end w-full lg:text-2xl gap-1 lg:gap-5">
            <span class="opacity-60">Bio</span>
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
