@php
    /**
     * @var \App\Models\Profile $profile
     */
@endphp
<div class="h-full w-full border-b-4 p-2 lg:rounded-full lg:border-4 lg:hover:bg-primary/50">
    <a href="{{ route('profile', ['profile' => $profile->nickname]) }}"
        class="grid grid-cols-3 items-center justify-center gap-10 rounded-full p-1 hover:bg-primary/50 lg:hover:bg-transparent">
        <div>
            <img src="{{ $profile->main_image }}" alt="{{ $profile->nickname . ' ' . 'main image' }}"
                class="aspect-[1/1] rounded-full object-cover">
        </div>
        <div class="col-span-2 flex flex-col">
            <span class="opacity-60">{{ $profile->type }}</span>
            <span>{{ '@' . $profile->nickname }}</span>
        </div>

    </a>
</div>
