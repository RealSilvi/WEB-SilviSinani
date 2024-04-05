@extends('layouts.default', [
    'title' => __('pages.profile.new.title'),
])

@section('main')
    <main class="mx-auto w-full max-w-screen-2xl flex-1">
        <section class="w-full mx-auto max-w-screen-2xl flex flex-1 items-center justify-center">
            <div class="p-10 lg:px-20 xl:pb-20 w-full">
                <div class="flex flex-col items-center justify-center gap-10">
                    <x-image class="h-12 w-12 lg:h-20 lg:w-20 object-cover rounded-full" filter="logo green" />
                    <div class="text-center lg:text-start lg:px-10 xl:px-20 text-3xl xl:text-4xl w-full font-medium">
                        {{ __('pages.profile.new.title') }}
                    </div>
                </div>

                <div class="mt-10 lg:mt-15 xl:mt-20 flex flex-col">
                    <form action="/api/users/{{auth()->id()}}/profiles" method="post"
                          x-data="formSubmit()"
                          @submit.prevent="submit"
                          class="lg:px-10 xl:px-20">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-10 xl:gap-x-40 gap-y-3">
                            <x-form.group name="main_image">
                                <x-form.label sr-only>
                                    {{ __('form.profile_create.main_image') }}
                                </x-form.label>
                                <x-form.image-picker
                                        defaultUrlStorage="{{asset('/storage/utilities/pet-placeholder.png')}}"
                                        class="h-20" />
                            </x-form.group>
                            <div>
                                <x-form.group name="type">
                                    <x-form.label sr-only>
                                        {{ __('form.profile_create.type') }}
                                    </x-form.label>
                                    <x-form.input required value="{{ old('type') }}"
                                                  placeholder="{{ __('form.profile_create.type') }}"
                                                  class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                                </x-form.group>

                                <x-form.group name="nickname">
                                    <x-form.label sr-only>
                                        {{ __('form.profile_create.nickname') }}
                                    </x-form.label>
                                    <x-form.input required value="{{ old('nickname') }}"
                                                  placeholder="{{ __('form.profile_create.nickname') }}"
                                                  autocomplete="given-name"
                                                  class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                                </x-form.group>

                                <x-form.group name="date_of_birth">
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

    <footer>
        <div class="flex h-10 items-center justify-center bg-primary/20 text-xs text-primary">
            {{ env('APP_NAME') }}
        </div>
    </footer>
@endsection
