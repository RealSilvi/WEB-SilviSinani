@extends('layouts.auth', [
    'title' => __('pages.auth.reset_password.title'),
])

@section('content')
    <section class="w-full mx-auto max-w-screen-2xl flex flex-1 items-center justify-center">
        <div class="p-10 lg:px-20 xl:pb-20 w-full">
            <div class="flex flex-col items-center justify-center gap-10">
                <x-image class="h-12 w-12 lg:h-20 lg:w-20 object-cover rounded-full" filter="logo green" />
                <div class="text-center lg:text-start lg:px-10 xl:px-20 text-3xl xl:text-4xl w-full font-medium">
                    {{ __('pages.auth.reset_password.title') }}
                </div>
            </div>

            <div class="mt-10 lg:mt-15 xl:mt-20 flex flex-col">
                <form action="{{route('password.update')}}" method="post" class="lg:px-10 xl:px-20">
                    @csrf

                    @error('email')
                    <x-form.error-message>{{ $message }}</x-form.error-message>
                    @enderror

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ request('email') }}">


                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-5 lg:gap-x-10 xl:gap-x-40">

                        <x-form.group name="password">
                            <x-form.label sr-only>
                                {{ __('form.reset_password.password') }}
                            </x-form.label>
                            <x-form.input class="placeholder-primary placeholder:font-light"
                                          placeholder="{{ __('form.reset_password.password') }}" type="password" />
                        </x-form.group>

                        <x-form.group name="password_confirmation">
                            <x-form.label sr-only>
                                {{ __('form.reset_password.password_confirmation') }}
                            </x-form.label>
                            <x-form.input class="placeholder-primary placeholder:font-light"
                                          placeholder="{{ __('form.reset_password.password_confirmation') }}"
                                          type="password" />
                        </x-form.group>

                        <div class="hidden lg:block"></div>

                        <div class="mt-10 lg:mt-15 xl:mt-20">
                            <x-form.submit class="w-full rounded-full font-black">
                                {{ __('form.reset_password.submit_button') }}
                            </x-form.submit>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
