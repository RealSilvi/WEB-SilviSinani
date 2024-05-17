@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $followRequestProfile
     * @var \App\Models\Profile $authProfile
     * @var \Illuminate\Database\Eloquent\Collection<array-key,\App\Models\Profile> $pendingFollowers
     */
    $user = $user ?? auth()->user();

    $acceptFollowerFormId = 'accept_follower_form';
    $acceptFollowerMethod = 'POST';
    $acceptFollowerActionUrl = route('users.profiles.followers.store', [
        'user' => $user->id,
        'profile' => $authProfile->id,
    ]);

    $declineFollowerFormId = 'decline_follower_form';
    $declineFollowerMethod = 'DELETE';
    $declineFollowerActionUrl = route('users.profiles.followers.destroy', [
        'user' => $user->id,
        'profile' => $authProfile->id,
        'follower' => $followRequestProfile->id,
    ]);
@endphp
<div class="h-full w-full border-b-4 py-5 lg:rounded-full lg:border-4 lg:p-2 lg:hover:bg-primary/50">
    <div
        class="grid grid-cols-3 items-center justify-center gap-10 rounded-full p-1 hover:bg-primary/50 lg:hover:bg-transparent">
        <a
            href="{{ route('profile', ['profile' => $followRequestProfile->nickname, 'authProfile' => $authProfile->nickname]) }}">
            <img src="{{ asset($followRequestProfile->main_image) }}"
                alt="{{ $followRequestProfile->nickname . ' ' . 'main image' }}"
                class="aspect-[1/1] rounded-full object-cover">
        </a>
        <div class="flex flex-col">
            <span class="opacity-60">{{ $followRequestProfile->type }}</span>
            <a
                href="{{ route('profile', ['profile' => $followRequestProfile->nickname, 'authProfile' => $authProfile->nickname]) }}">
                {{ '@' . $followRequestProfile->nickname }}
            </a>
        </div>
        <div class="grid grid-cols-2">
            <form action="{{ $acceptFollowerActionUrl }}" method="{{ $acceptFollowerMethod }}" x-data="formSubmit({
                formId: '{{ $acceptFollowerFormId }}',
                url: '{{ $acceptFollowerActionUrl }}',
                method: '{{ $acceptFollowerMethod }}',
            })"
                @submit.prevent="submit" class="h-full w-full items-center justify-center">
                @csrf
                <x-form.group name="followerId">
                    <x-form.input type="hidden" value="{{ $followRequestProfile->id }}"></x-form.input>
                </x-form.group>
                <button @click="submit">
                    {{ svg('accept', 'h-8 w-8 text-success/75') }}
                </button>
            </form>
            <form action="{{ $declineFollowerActionUrl }}" method="{{ $declineFollowerMethod }}"
                x-data="formSubmit({
                    formId: '{{ $declineFollowerFormId }}',
                    url: '{{ $declineFollowerActionUrl }}',
                    method: '{{ $declineFollowerMethod }}',
                })" @submit.prevent="submit" class="h-full w-full items-center justify-center">
                @csrf
                <button @click="submit">
                    {{ svg('decline', 'h-8 w-8 text-error/75') }}
                </button>
            </form>
        </div>

    </div>
</div>
