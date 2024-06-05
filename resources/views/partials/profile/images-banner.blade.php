@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var boolean $ownership
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $quickEditImagesForm
     */

    $user = $user ?? auth()->user();
    $profile = $profile ?? $user->getDefaultProfile();
    $ownership = $ownership ?? false;
@endphp

<div x-cloak
     x-data="{ showEditBackgroundImageModal: false, showEditProfileImageModal: false }"
     x-effect="document.body.style.overflow = showEditBackgroundImageModal || showEditProfileImageModal ? 'hidden' : ''">
    <div>
        <button @click="showEditBackgroundImageModal = true" class="w-full">
            <img src="{{asset($profile->secondary_image)}}" alt="{{$profile->nickname.' secondary image'}}"
                 class="object-cover aspect-[7/3] w-full rounded-xl" />
        </button>
    </div>

    <div class="-mt-5 lg:relative px-2 ">
        <div
            class="grid grid-cols-3 w-full justify-around items-center gap-5 lg:absolute lg:-translate-y-2/3 ">
            <button
                @click="showEditProfileImageModal = true">
                <img src="{{ asset($profile->main_image) }}" alt="{{ $profile->nickname.' main image' }}"
                     class="block aspect-[1/1] w-full rounded-full object-cover" />
            </button>

            <div
                class="flex flex-col justify-end h-full lg:order-first lg:flex-row lg:gap-10 lg:p-3 lg:items-end lg:justify-center lg:text-2xl">
                <span class="opacity-60">{{ $profile->type }}</span>
                <span>{{ '@'.$profile->nickname }}</span>
            </div>
            <div></div>
        </div>
    </div>

    <div x-show="showEditBackgroundImageModal"
         class="fixed h-full w-full bg-black/50 z-50 inset-0 px-5 pb-16 pt-20 lg:pb-5 lg:pr-20">
        <div
            class="relative h-full w-full bg-black/50 rounded-xl inset-0 flex flex-col items-center gap-2 justify-center py-10 px-5 lg:p-12">
            <button class="absolute right-2 top-2 lg:right-5 lg:top-5"
                    @click="showEditBackgroundImageModal = false">
                {{svg('close','h-5 w-5 lg:h-7 lg:w-7 text-white')}}
            </button>

            <div
                class="flex items-center justify-center h-5/6">
                <img src="{{ asset($profile->secondary_image) }}" alt="{{ $profile->nickname.' secondary image' }}"
                     class="object-contain w-full h-full rounded-xl" />
            </div>
            @if($ownership)
                <div class="w-full h-16 lg:h-12">
                    <form class=" h-full w-full flex flex-col items-center justify-between gap-2"
                          x-data="formSubmit({
                              formId: '{{ $quickEditImagesForm['id'] }}',
                              url: '{{ $quickEditImagesForm['action'] }}',
                              method: '{{ $quickEditImagesForm['method'] }}',
                          })"
                          @submit.prevent="submit">
                        @csrf
                        <x-form.group name="secondaryImage" class=" rounded-xl bg-primary px-3 w-fit">
                            <x-form.label sr-only>
                                {{ __('form.profile_edit.secondary_image') }}
                            </x-form.label>

                            <x-form.image-picker class="h-20 lg:text-xl text-white" />
                        </x-form.group>

                        <x-form.submit class="rounded-full font-black lg:font-medium px-10 py-2">
                            {{ $quickEditImagesForm['submitLabel'] }}
                        </x-form.submit>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div x-show="showEditProfileImageModal"
         class="fixed h-full w-full bg-black/50 z-50 inset-0 px-5 pb-16 pt-20 lg:pb-5 lg:pr-20">
        <div class="relative h-full w-full bg-black/50 rounded-xl inset-0 flex flex-col items-center gap-2 justify-center py-10 px-5 lg:p-12">
            <button class="absolute right-2 top-2 lg:right-5 lg:top-5" @click="showEditProfileImageModal = false">
                {{ svg('close','h-5 w-5 lg:h-7 lg:w-7 text-white') }}
            </button>

            <div class="flex items-center justify-center h-5/6">
                <img src="{{ asset($profile->main_image) }}" alt="{{ $profile->nickname.' secondary image' }}"
                     class="object-contain w-full h-full rounded-xl" />
            </div>

            @if($ownership)
                <div class="w-full h-16 lg:h-12">
                    <form class=" h-full w-full flex flex-col items-center justify-between gap-2"
                        x-data="formSubmit({
                            formId: '{{ $quickEditImagesForm['id'] }}',
                            url: '{{ $quickEditImagesForm['action'] }}',
                            method: '{{ $quickEditImagesForm['method'] }}',
                        })"
                          @submit.prevent="submit">
                        @csrf
                        <x-form.group name="mainImage" class=" rounded-xl bg-primary px-3 w-fit">
                            <x-form.label sr-only>
                                {{ __('form.profile_edit.secondary_image') }}
                            </x-form.label>

                            <x-form.image-picker class="h-20 lg:text-xl text-white" />
                        </x-form.group>

                        <x-form.submit class="rounded-full font-black lg:font-medium px-10 py-2">
                            {{ $quickEditImagesForm['submitLabel'] }}
                        </x-form.submit>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
