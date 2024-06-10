@php

    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;

@endphp

@extends('layouts.default',[
    'title'=> __('pages.profiles.new.title')
])

@section('main')
    <header>
        @include('partials.navbar.navbar', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
        ])
    </header>

    <aside>
        @include('partials.sidebar.sidebar', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
        ])
    </aside>

    <main class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">
        <section class="flex w-full h-full items-center justify-center">
            <form x-data="profile({ userId: {{ $user->id }} })"
                  @submit.prevent="createProfile"
                  @create-profile="window.location.replace('/')" >
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
                                <x-form.image-picker-preview
                                        defaultUrlStorage="{{asset('/storage/utilities/profileDefault.jpg')}}"
                                        class="h-20" />
                            </x-form.group>
                        </div>
                    </div>
                    <div class="flex flex-col mt-5 lg:mt-0">
                        <div class="lg:order-first lg:pb-10">
                            <x-form.group name="type">
                                <div class="flex flex-row items-center justify-center w-full gap-2 lg:gap-4 flex-wrap">
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
                        <x-form.group name="nickname" class="mt-10 lg:mt-0">
                            <x-form.label sr-only>
                                {{ __('form.profile_create.nickname') }}
                            </x-form.label>
                            <x-form.input required value="{{ old('nickname') }}"
                                          placeholder="{{ __('form.profile_create.nickname') }}"
                                          autocomplete="given-name"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <x-form.group name="breed">
                            <x-form.label sr-only>
                                {{ __('form.profile_create.breed') }}
                            </x-form.label>
                            <x-form.input value="{{ old('breed') }}"
                                          placeholder="{{ __('form.profile_create.breed') }}"
                                          autocomplete="family-name"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <x-form.group name="dateOfBirth">
                            <x-form.label sr-only>
                                {{ __('form.register.date_of_birth') }}
                            </x-form.label>
                            <x-form.date value="{{ old('date_of_birth') }}"
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

                        <x-form.group name="default" type="checkbox" class="mt-5">
                            <x-form.checkbox value="{{old('default')}}" value="1" />
                            <x-form.label class="text-primary font-bold text-sm xl:text-lg">
                                {{ __('form.profile_create.default') }}
                            </x-form.label>
                        </x-form.group>

                        <div class="mt-11 xl:mt-20">
                            <div class="flex flex-col items-center justify-center">
                                <x-form.submit class="w-full rounded-full font-black">
                                    {{  __('form.profile_create.submit_button') , }}
                                </x-form.submit>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>

@endsection
