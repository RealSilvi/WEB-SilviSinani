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
    'title'=> __('pages.settings.title')
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
        @include('partials.sidebar.sidebar',[
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
        ])
    </aside>

    <main  x-data="profile({ userId: {{ $user->id }} })"
        class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">
        <div class="flex flex-col gap-10 lg:grid lg:grid-cols-2 lg:gap-x-32 lg:gap-y-20">
            <div class="text-center lg:text-start text-4xl xl:text-4xl w-full font-medium">
                {{ __('pages.settings.edit_profile') }}
            </div>

            <form class="lg:col-span-2"
                  @submit.prevent="updateProfile($event,{{$profile->id}})"
                  @update-profile="window.location.replace('/')"
            >
                @csrf
                <div class="flex flex-col gap-8 lg:grid lg:grid-cols-2 lg:gap-y-14 lg:gap-x-32">
                    <x-form.group name="mainImage" class="w-full">
                        <x-form.label class="lg:text-2xl lg:font-medium">
                            {{ __('form.profile_edit.main_image') }}
                        </x-form.label>

                        <x-form.image-picker class="h-20 lg:text-xl" />
                    </x-form.group>

                    <x-form.group name="secondaryImage" class="w-full">
                        <x-form.label class="lg:text-2xl lg:font-medium">
                            {{ __('form.profile_edit.secondary_image') }}
                        </x-form.label>

                        <x-form.image-picker class="h-20 lg:text-xl" />
                    </x-form.group>

                    <x-form.group name="nickname">
                        <x-form.label class="lg:text-2xl lg:font-medium">
                            {{ __('form.profile_edit.nickname') }}
                        </x-form.label>
                        <x-form.input placeholder="{{ $profile->nickname }}"
                                      class="placeholder-primary placeholder:font-light text-sm lg:text-xl" />
                    </x-form.group>

                    <x-form.group name="dateOfBirth">
                        <x-form.label class="lg:text-2xl lg:font-medium">
                            {{ __('form.profile_edit.date_of_birth') }}
                        </x-form.label>
                        <x-form.date placeholder="{{ $profile->date_of_birth }}"
                                     class="placeholder-primary placeholder:font-light text-sm lg:text-xl" />
                    </x-form.group>

                    <x-form.group name="type">
                        <x-form.label class="lg:text-2xl lg:font-medium">
                            {{ __('form.profile_edit.animal') }}
                        </x-form.label>
                        <x-form.select
                            placeholder="{{$profile->type->value}}"
                            class="!text-primary !font-light text-sm lg:text-xl">
                            @foreach(\App\Enum\ProfileType::cases() as $profileType)
                                @if($profileType != $profile->type)
                                    <option value="{{$profileType->value}}">
                                        {{$profileType->value}}
                                    </option>
                                @endif
                            @endforeach
                        </x-form.select>
                    </x-form.group>

                    <x-form.group name="breed">
                        <x-form.label class="lg:text-2xl lg:font-medium">
                            {{ __('form.profile_edit.breed') }}
                        </x-form.label>
                        <x-form.input placeholder="{{ $profile->breed }}"
                                      class="placeholder-primary placeholder:font-light text-sm lg:text-xl" />
                    </x-form.group>


                    <x-form.group name="bio">
                        <x-form.label class="lg:text-2xl lg:font-medium">
                            {{ __('form.profile_edit.bio') }}
                        </x-form.label>
                        <x-form.textarea rows="5"
                                         placeholder="{{$profile->bio}}"
                                         class="placeholder-primary placeholder:font-light text-sm lg:text-xl" />
                    </x-form.group>

                    <div class="flex-1 flex flex-col justify-end gap-10">
                        @if(!$authProfile->default)
                            <x-form.group name="default" type="checkbox" class="mt-5">
                                <x-form.checkbox value="{{old('default')}}" value="1" />
                                <x-form.label class="text-primary font-bold text-sm xl:text-lg">
                                    {{ __('form.profile_create.default') }}
                                </x-form.label>
                            </x-form.group>
                        @endif

                        <div class="flex items-end">
                            <x-form.submit class="w-full rounded-full font-black lg:font-medium">
                                {{ __('form.profile_edit.submit_button')}}
                            </x-form.submit>
                        </div>
                    </div>
                </div>
            </form>

            <hr class=" border-primary col-span-2">

            <div class="flex flex-col gap-10 lg:gap-10">
                <div class="text-center lg:text-start text-4xl xl:text-4xl w-full font-medium">
                    {{ __('pages.settings.delete_profile') }}
                </div>

                <div class="text-pretty lg:text-2xl font-medium text-primary">
                    {{ __('pages.settings.delete_message') }}
                </div>
            </div>
            <form class="flex items-end"
                  @submit.prevent="destroyProfile({{$profile->id}})"
                  @destroy-profile="window.location.replace('/')"
            >
                @csrf

                <x-form.submit class="!bg-red-900 w-full rounded-full font-black">
                    {{__('form.profile_edit.delete_button')}}
                </x-form.submit>
            </form>

            <hr class="border-primary col-span-2">

            <div class="text-center lg:text-start text-4xl xl:text-4xl w-full font-medium">
                {{ __('pages.settings.edit_language') }}
            </div>

            <div class="flex flex-row justify-around">
                @foreach(config('app.available_locales') as $key => $value)
                    <a
                        class="w-1/3 flex items-center justify-center p-2 lg:py-3 lg:px-5 rounded-full h-full lg:text-sm font-black
                         {{App::currentLocale()==$value?'bg-primary text-white':'text-primary bg-primary/50'}}"
                        href="{{ route('locales', [ 'locale' => $value ]) }}">
                        {{$key}}
                    </a>
                @endforeach
            </div>
        </div>
    </main>

@endsection
