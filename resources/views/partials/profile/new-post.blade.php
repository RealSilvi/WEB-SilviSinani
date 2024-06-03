@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     */
    $user = $user ?? auth()->user();
@endphp

@if($profile)
    <div>
        <a href="#" class="flex flex-row gap-2 items-center lg:gap-5 lg:text-xl italic">
            <img
                alt="{{$profile->nickname.'-image'}}"
                src="{{asset($profile->main_image)}}"
                class="h-7 w-7 lg:h-14 lg:w-14 rounded-full object-cover" />
            <div>{{'@'.$profile->nickname}}</div>
        </a>

        <form x-data="createPost({ userId: {{$user->id}}, profileId: {{$profile->id}} })"
              @submit.prevent="execute"
              action=""
              enctype="multipart/form-data"
              class="mt-5">
            <div class="relative">
                <x-form.group name="description">
                    <x-form.label sr-only>
                        crea post
                    </x-form.label>
                    <x-form.textarea rows="1"
                                     placeholder="Create post"
                                     class="placeholder-primary placeholder:font-light text-sm xl:text-lg !border py-2 pr-12 pl-2 rounded-xl flex-shrink" />
                </x-form.group>
                <x-form.submit class="absolute right-2 top-0 bg-transparent rounded-full !py-2">
                    {{svg('send','h-5 w-5 lg:h-6 lg:w-6 text-primary')}}
                </x-form.submit>
            </div>

            <x-form.group name="image" class="w-full">
                <x-form.label sr-only>
                    image
                </x-form.label>
                <x-form.image-picker
                    defaultUrlStorage="{{asset('/storage/utilities/profileDefault.jpg')}}"
                    class="h-20" />
            </x-form.group>
        </form>
    </div>
@endif

