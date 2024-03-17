@extends('layouts.auth', [
    'title' => 'Login',
])

@section('content')
    <section class="w-full mx-auto max-w-screen-2xl flex flex-1 items-center justify-center">
        <div class="p-10 lg:px-20 xl:pb-20 w-full">
            <div class="flex items-center justify-center">
                <x-image class="h-12 w-12 lg:h-20 lg:w-20 object-cover rounded-full" filter="logo green" />
            </div>

            <div class="mt-10 lg:mt-15 xl:mt-20 flex flex-col lg:grid lg:grid-cols-2 lg:items-center lg:justify-center lg:gap-10 xl:gap-0">
                <div class="hidden lg:block px-10 xl:px-24">
                    <x-image class="aspect-[4/5] object-cover" filter="pet green" />
                </div>
                <form action="/auth/login" method="post" class="lg:px-10 xl:px-20">
                    @csrf
                    <x-form.group name="email">
                        <x-form.label sr-only>
                            e-mail
                        </x-form.label>
                        <x-form.input type="email" required value="{{ old('email') }}"
                                      placeholder="email"
                                      autocomplete="email"
                                      class="placeholder-primary placeholder:font-light text-sm xl:text-lg"
                        />
                    </x-form.group>

                    <x-form.group name="password">
                        <x-form.label sr-only>
                            password
                        </x-form.label>
                        <x-form.input type="password" required placeholder="password"
                                      autocomplete="current-password"
                                      class="mt-9 xl:mt-14 placeholder-primary placeholder:font-light text-sm xl:text-lg"
                        />
                    </x-form.group>

                    <div class="mt-11 xl:mt-20">
                        <div class="flex flex-col items-center justify-center">
                            <x-form.submit class="w-full rounded-full font-black">
                                Accedi
                            </x-form.submit>

                            <div class="mt-3 text-xs  lg::mt-6 xl:text-base">
                                <a href="{{ url('/auth/forgot-password') }}" class="font-black">
                                    Recupera password
                                </a>
                            </div>

                            <div class="w-full flex justify-between mt-5">
                                <div class="grow border-b border-primary"></div>
                                <div class="translate-y-1.5 text-xs px-2">oppure</div>
                                <div class="grow border-b border-primary"></div>
                            </div>

                            <div class="mt-10 flex xitems-center justify-center">
                                <a href="{{ url('/auth/register') }}"
                                   class="font-black text-xs bg-gray-100 rounded-full px-4 py-2">
                                    Crea un nuovo account
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection
