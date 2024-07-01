@php
    /**
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $registerForm
     */
    $registerForm = [
            'id' => 'register',
            'method' => 'POST',
            'action' => route('register'),
            'submitLabel' =>  __('form.register.submit_button')
        ];
@endphp
@extends('layouts.auth', [
    'title' => __('pages.auth.register.title'),
])

@section('main')
    <section class="w-full mx-auto max-w-screen-2xl flex flex-1 items-center justify-center">
        <div class="p-10 lg:px-20 xl:pb-20 w-full">
            <div class="flex flex-col items-center justify-center gap-10">
                {{svg('other-logo','h-12 w-12 lg:h-20 lg:w-20 rounded-full')}}
                <div class="text-center lg:text-start lg:px-10 xl:px-20 text-3xl xl:text-4xl w-full font-medium">
                    {{ __('pages.auth.register.title') }}
                </div>
            </div>

            <div class="mt-10 lg:mt-15 xl:mt-20 flex flex-col">
                <form class="lg:px-10 xl:px-20"
                      action="{{$registerForm['action']}}"
                      method="{{$registerForm['method']}}">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-10 xl:gap-x-40 gap-y-3">

                        <x-form.group name="first_name">
                            <x-form.label sr-only>
                                {{ __('form.register.first_name') }}
                            </x-form.label>
                            <x-form.input required value="{{ old('first_name') }}"
                                          placeholder="  {{ __('form.register.first_name') }}" autocomplete="given-name"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <x-form.group name="last_name">
                            <x-form.label sr-only>
                                {{ __('form.register.last_name') }}
                            </x-form.label>
                            <x-form.input required value="{{ old('last_name') }}"
                                          placeholder="  {{ __('form.register.last_name') }}" autocomplete="family-name"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <x-form.group name="email">
                            <x-form.label sr-only>
                                {{ __('form.register.email') }}
                            </x-form.label>
                            <x-form.input required value="{{ old('email') }}"
                                          placeholder="  {{ __('form.register.email') }}" autocomplete="email"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <x-form.group name="date_of_birth">
                            <x-form.label sr-only>
                                {{ __('form.register.date_of_birth') }}
                            </x-form.label>
                            <x-form.date required value="{{ old('date_of_birth') }}"
                                         placeholder="  {{ __('form.register.date_of_birth') }}" autocomplete="date_of_birth"
                                         class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <x-form.group name="password">
                            <x-form.label sr-only>
                                {{ __('form.register.password') }}
                            </x-form.label>
                            <x-form.input required type="password"
                                          placeholder="  {{ __('form.register.password') }}" autocomplete="new-password"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <x-form.group name="password_confirmation">
                            <x-form.label sr-only>
                                {{ __('form.register.password_confirmation') }}
                            </x-form.label>
                            <x-form.input required
                                          type="password"
                                          placeholder="  {{ __('form.register.password_confirmation') }}"
                                          autocomplete="new-password"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>
                        <div class="hidden lg:block"></div>

                        <div class="mt-11 xl:mt-20">
                            <div class="flex flex-col items-center justify-center">
                                <x-form.submit class="w-full rounded-full font-black">
                                    {{ $registerForm['submitLabel'] }}
                                </x-form.submit>

                                <div class="mt-3 text-xs lg:mt-6 xl:text-base">
                                    <a href="{{ url('/auth/login') }}" class="font-black">
                                        {{ __('form.register.already_registered_link') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
