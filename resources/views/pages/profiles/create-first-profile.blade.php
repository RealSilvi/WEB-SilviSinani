@php
    $formId = 'register_profile_form';
    $actionUrl = route('users.profiles.store',['user'=>auth()->id()]);
    $onSuccessRedirect=\App\Providers\RouteServiceProvider::HOME;
@endphp

@extends('layouts.auth', [
    'title' => __('pages.profile.new.title'),
])

@section('main')
    <main class="mx-auto w-full max-w-screen-2xl flex-1">
        <section class="w-full h-screen flex items-center justify-center">
            <div class="p-10 lg:px-20 xl:pb-20 w-full">
                <div class="flex items-center justify-center">
                    <x-image class="h-12 w-12 lg:h-20 lg:w-20 object-cover rounded-full" filter="logo green" />
                </div>

                <div class="mt-10 lg:mt-15  flex flex-col">
                    <form action="{{$actionUrl}}"
                          method="POST"
                          x-data="formSubmit({
                            formId: '{{ $formId }}',
                            url: '{{ $actionUrl }}',
                            onSuccessRedirectUrl: '{{ $onSuccessRedirect }}',
                          })"
                          @submit.prevent="submit">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-10 xl:gap-x-40 gap-y-3">
                            <div class="flex flex-col gap-7 lg:gap-10">
                                <div class="text-center lg:text-start text-3xl xl:text-4xl w-full font-medium">
                                    {{ __('pages.profile.new.title') }}
                                </div>
                                <div class="flex-1 flex items-center justify-center">
                                    <x-form.group name="mainImage" class="w-full">
                                        <x-form.label sr-only>
                                            {{ __('form.profile_create.main_image') }}
                                        </x-form.label>
                                        <x-form.image-picker
                                            defaultUrlStorage="{{asset('/storage/utilities/pet-placeholder.png')}}"
                                            class="h-20" />
                                    </x-form.group>
                                </div>
                            </div>
                            <div class="flex flex-col mt-5 lg:mt-0">
                                <x-form.group name="nickname">
                                    <x-form.label sr-only>
                                        {{ __('form.profile_create.nickname') }}
                                    </x-form.label>
                                    <x-form.input required value="{{ old('nickname') }}"
                                                  placeholder="{{ __('form.profile_create.nickname') }}"
                                                  autocomplete="given-name"
                                                  class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                                </x-form.group>

                                <x-form.group name="dateOfBirth">
                                    <x-form.label sr-only>
                                        {{ __('form.register.date_of_birth') }}
                                    </x-form.label>
                                    <x-form.date required value="{{ old('date_of_birth') }}"
                                                 placeholder="  {{ __('form.profile_create.date_of_birth') }}"
                                                 autocomplete="bday"
                                                 class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                                </x-form.group>

                                <x-form.group name="bio">
                                    <x-form.label sr-only>
                                        {{ __('form.profile_create.bio') }}
                                    </x-form.label>
                                    <x-form.textarea rows="3"
                                                     placeholder="{{ __('form.profile_create.bio') }}"
                                                     class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                                </x-form.group>

                                <div class="lg:order-first mt-10 lg:mt-0 lg:pb-10">

                                    <x-form.group name="type">
                                        <div
                                            class="flex flex-row items-center justify-center w-full gap-2 lg:gap-4 flex-wrap ">
                                            @foreach(\App\Enum\ProfileType::cases() as $profileType)
                                                <div class="w-1/4">

                                                    <x-form.radio
                                                        id="{{ $profileType->value }}"
                                                        value="{{ $profileType->value }}"
                                                        class="hidden peer/type" />

                                                    <x-form.label
                                                        for="{{ $profileType->value }}"
                                                        class="flex items-center justify-center p-2 lg:py-3 lg:px-5 rounded-xl text-primary w-full h-full cursor-pointer text-sm bg-primary/50 peer-checked/type:bg-primary peer-checked/type:text-white">
                                                        {{ $profileType->value }}
                                                    </x-form.label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </x-form.group>
                                </div>

                                <div class="mt-11 xl:mt-20">
                                    <div class="flex flex-col items-center justify-center">
                                        <x-form.submit class="w-full rounded-full font-black">
                                            {{ __('form.profile_create.submit_button') }}
                                        </x-form.submit>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
