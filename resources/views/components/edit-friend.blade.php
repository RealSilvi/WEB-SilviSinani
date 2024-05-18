@php
    /**
     * @var \App\Models\Profile $friendProfile
     * @var \App\Models\Profile $authProfile
     * @var bool $onlyDelete
     * @var array{id:string,method:string,action:string} $storeFriendForm
     * @var array{id:string,method:string,action:string} $deleteFriendForm
     */
@endphp
<div class="h-full w-full border-b-4 py-5 lg:rounded-full lg:border-4 lg:p-2 lg:hover:bg-primary/50">
    <div
        class="grid grid-cols-3 items-center justify-center gap-10 rounded-full p-1 hover:bg-primary/50 lg:hover:bg-transparent">
        <a
            href="{{ route('profile', ['profile' => $friendProfile->nickname, 'authProfile' => $authProfile->nickname]) }}">
            <img src="{{ asset($friendProfile->main_image) }}" alt="{{ $friendProfile->nickname . ' ' . 'main image' }}"
                class="aspect-[1/1] rounded-full object-cover">
        </a>
        <div class="flex flex-col">
            <span class="opacity-60">{{ $friendProfile->type }}</span>
            <a
                href="{{ route('profile', ['profile' => $friendProfile->nickname, 'authProfile' => $authProfile->nickname]) }}">
                {{ '@' . $friendProfile->nickname }}
            </a>
        </div>
        <div class="grid grid-cols-2">

            @if (!$onlyDelete)
                <form action="{{ $storeFriendForm['action'] }}" method="{{ $storeFriendForm['method'] }}"
                    x-data="formSubmit({
                        formId: '{{ $storeFriendForm['id'] }}',
                        url: '{{ $storeFriendForm['action'] }}',
                        method: '{{ $storeFriendForm['method'] }}',
                    })" @submit.prevent="submit" class="h-full w-full items-center justify-center">
                    @csrf
                    <x-form.group name="followerId">
                        <x-form.input type="hidden" value="{{ $friendProfile->id }}"></x-form.input>
                    </x-form.group>
                    <button @click="submit">
                        {{ svg('accept', 'h-8 w-8 text-success/75') }}
                    </button>
                </form>
            @endif

            <form action="{{ $deleteFriendForm['action'] }}" method="{{ $deleteFriendForm['method'] }}"
                x-data="formSubmit({
                    formId: '{{ $deleteFriendForm['id'] }}',
                    url: '{{ $deleteFriendForm['action'] }}',
                    method: '{{ $deleteFriendForm['method'] }}',
                })" @submit.prevent="submit"
                class="order-last h-full w-full items-center justify-center">
                @csrf
                <button @click="submit">
                    {{ svg('decline', 'h-8 w-8 text-error/75') }}
                </button>
            </form>
        </div>

    </div>
</div>
