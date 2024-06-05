@php
    /**
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $verifyEmailForm
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $logoutForm
     */

    $verifyEmailForm = [
            'id' => 'verify_email',
            'method' => 'POST',
            'action' => route('verification.send'),
            'submitLabel' =>   __('form.verify_email.submit_button'),
        ];

    $logoutForm = [
            'id' => 'logout',
            'method' => 'POST',
            'action' => route('logout'),
            'submitLabel' =>   __('Logout'),
        ];
@endphp
@extends('layouts.auth', [
    'title' => __('pages.auth.forgot_password.title'),
])

@section('main')
    <section class="w-full mx-auto max-w-screen-2xl flex-1 flex flex-col items-center justify-center relative">
        <div class="p-10 lg:px-20 xl:pb-20 w-full">
            <div
                class="flex flex-col lg:grid lg:grid-cols-2 xl:gap-40 items-center justify-center gap-10 lg:px-10 xl:px-20">
                <div class="flex items-center justify-center">
                    <x-image class="h-12 w-12 lg:h-20 lg:w-20 object-cover rounded-full" filter="logo green" />
                </div>
                <div class="w-full lg:order-first flex flex-col gap-10">
                    <div class="text-center lg:text-start text-3xl xl:text-4xl font-medium">
                        {{ __('pages.auth.verify_email.title') }}
                    </div>
                    <div class="text-lg text-center">
                        {{ __('form.verify_email.message') }}
                    </div>
                </div>
            </div>

            <div class="mt-10 lg:mt-15 xl:mt-20">
                <form class="lg:px-10 xl:px-20"
                      action="{{$verifyEmailForm['action']}}"
                      method="{{$verifyEmailForm['method']}}">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-10 xl:gap-40">

                        <div class="mt-11 lg:mt-0">
                            <div class="flex flex-col items-center justify-center">
                                <x-form.submit class="w-full rounded-full font-black">
                                    {{$verifyEmailForm['submitLabel'] }}
                                </x-form.submit>
                            </div>
                        </div>
                    </div>
                    @if (session('status') == 'verification-link-sent')
                        <div class="mt-5 text-center lg:grid lg:grid-cols-2">
                            {{ __('form.verify_email.onsuccess') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="absolute right-10 lg:right-20 bottom-10">
            <form action="{{$logoutForm['action']}}"
                  method="{{$logoutForm['method']}}">
                @csrf
                <button class="font-medium">
                    {{$logoutForm['submitLabel'] }}
                </button>
            </form>
        </div>
    </section>
@endsection
