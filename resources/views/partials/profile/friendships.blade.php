@php
    /**
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var boolean $ownership
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $friendshipRequestForm
     */
@endphp

<div class="mt-5 flex flex-row gap-2 lg:gap-10 justify-between items-center h-full lg:mt-40 lg:text-2xl">

    <a class="bg-primary/10 lg:text-2xl rounded-xl py-2 w-full lg:p-3 lg:w-2/3 h-fit text-center lg:text-start"
       href="{{ route('friendships',[
            'profile'=>$profile->nickname,
            'friendshipType'=>\App\Enum\FriendshipType::FOLLOWER->value,
            'authProfile'=>$authProfile->nickname]) }}">
        Followers <br class="lg:hidden"> {{ $profile->followers_count }}
    </a>

    @if(!$ownership)
        <form action="{{ $friendshipRequestForm['action'] }}"
              method="{{ $friendshipRequestForm['method'] }}"
              x-data="formSubmit({
                  formId: '{{ $friendshipRequestForm['id'] }}',
                  url: '{{ $friendshipRequestForm['action'] }}',
                  method: '{{ $friendshipRequestForm['method'] }}',
                  })"
              @submit.prevent="submit"
              class="w-full h-full">
            @csrf
            <x-form.group name="followerId">
                <x-form.input type="hidden" value="{{ $profile->id }}"></x-form.input>
            </x-form.group>

            <x-form.submit
                    class="bg-primary/10 font-normal lg:text-2xl rounded-xl py-2 w-full lg:p-3 h-full lg:h-fit !text-primary">
                {{ $friendshipRequestForm['submitLabel'] }}
            </x-form.submit>
        </form>

    @endif

    <a class="bg-primary/10 lg:text-2xl rounded-xl py-2 w-full lg:p-3 lg:w-2/3 h-fit text-center lg:text-start"
       href="{{ route('friendships', [
            'profile' => $profile->nickname,
            'friendshipType' => \App\Enum\FriendshipType::FOLLOWING->value,
            'authProfile' => $authProfile->nickname]) }}">
        Following <br class="lg:hidden"> {{ $profile->following_count }}
    </a>
</div>
