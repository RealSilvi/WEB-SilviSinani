@extends('layouts.auth', [
    'title' => __('pages.auth.forgot_password.title'),
])

@section('content')
    <section class="w-full mx-auto max-w-screen-2xl flex flex-1 items-center justify-center">
        <div class="p-10 lg:px-20 xl:pb-20 w-full">
            <div
                class="flex flex-col lg:grid lg:grid-cols-2 xl:gap-40 items-center justify-center gap-10 lg:px-10 xl:px-20">
                <div class="flex items-center justify-center">
                    <x-image class="h-12 w-12 lg:h-20 lg:w-20 object-cover rounded-full" filter="logo green" />
                </div>

                <div class="w-full lg:order-first flex flex-col gap-10">
                    <div class="text-center lg:text-start text-3xl xl:text-4xl font-medium">
                        {{ __('pages.auth.forgot_password.title') }}
                    </div>
                    <div class="text-lg">
                        {{ __('form.forgot_password.message') }}
                    </div>
                </div>

            </div>

            <div class="mt-10 lg:mt-15 xl:mt-20">
                <form action="{{route('password.email')}}" method="post" class="lg:px-10 xl:px-20">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-10 xl:gap-40">
                        <x-form.group name="email">
                            <x-form.label sr-only>
                                {{ __('form.forgot_password.email') }}
                            </x-form.label>
                            <x-form.input type="email" required placeholder="{{ __('form.forgot_password.email') }}"
                                          value="{{ old('email') }}"
                                          class="placeholder-primary placeholder:font-light text-sm xl:text-lg" />
                        </x-form.group>

                        <div class="mt-11 lg:mt-0">
                            <div class="flex flex-col items-center justify-center">
                                <x-form.submit class="w-full rounded-full font-black">
                                    {{ __('form.forgot_password.submit_button') }}
                                </x-form.submit>

                                <div class="mt-3 text-xs  lg::mt-6 xl:text-base">
                                    <a href="{{ url('/auth/login') }}" class="font-black">
                                        {{ __('form.register.already_registered_link') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (session('status'))
                        <div class="mt-5 text-center">
                            {{ session('status') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
