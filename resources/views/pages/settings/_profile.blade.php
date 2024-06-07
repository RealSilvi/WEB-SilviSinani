@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $editForm
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $deleteForm
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;

    $editForm = [
            'id' => 'edit_profile',
            'method' => 'PATCH',
            'action' => route('users.profiles.update',['user'=>$user->id,'profile'=>$profile->id]),
            'redirectUrl' => route('home'),
            'submitLabel' =>  __('form.profile_edit.submit_button'),
        ];

    $deleteForm = [
            'id' => 'delete_profile',
            'method' => 'DELETE',
            'action' => route('users.profiles.destroy',['user'=>$user->id,'profile'=>$profile->id]),
            'redirectUrl' => route('home'),
            'submitLabel' => __('form.profile_edit.delete_button'),
        ];

@endphp
@extends('layouts.default')

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

    <main class="mx-auto w-full max-w-screen-2xl flex-1 pt-5 pb-10 lg:pt-20 lg:pb-32 px-5 lg:px-20">
        <div class="flex flex-col gap-10 lg:grid lg:grid-cols-2 lg:gap-x-32 lg:gap-y-20">
            <div class="text-center lg:text-start text-4xl xl:text-4xl w-full font-medium">
                {{ __('pages.profile.edit.title') }}
            </div>

            <form class="lg:col-span-2"
                  x-data="formSubmit({
                      formId: '{{ $editForm['id'] }}',
                      url: '{{ $editForm['action'] }}',
                      method: '{{ $editForm['method'] }}',
                      onSuccessRedirectUrl: '{{ $editForm['redirectUrl'] }}',
                  })"
                  @submit.prevent="submit">
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
                        <x-form.textarea rows="3"
                                         placeholder="{{$profile->bio}}"
                                         class="placeholder-primary placeholder:font-light text-sm lg:text-xl" />
                    </x-form.group>

                    <div class="flex flex-col items-center justify-end ">
                        <x-form.submit class="w-full rounded-full font-black lg:font-medium">
                            {{ $editForm['submitLabel'] }}
                        </x-form.submit>
                    </div>
                </div>
            </form>

            <hr class=" border-primary col-span-2">

            <div class="flex flex-col gap-10 lg:gap-10">
                <div class="text-center lg:text-start text-4xl xl:text-4xl w-full font-medium">
                    Elimina profilo
                </div>

                <div class="text-pretty lg:text-2xl font-medium text-primary">
                    Attezione eliminerai definitivamente il tuo profilo. Non vi e possibilita di recupero
                </div>
            </div>
            <form class="flex items-end" x-data="formSubmit({
                      formId: '{{ $deleteForm['id'] }}',
                      url: '{{ $deleteForm['action'] }}',
                      method: '{{ $deleteForm['method'] }}',
                      onSuccessRedirectUrl: '{{ $deleteForm['redirectUrl'] }}',
                  })"
                  @submit.prevent="submit">
                @csrf

                <x-form.submit class="!bg-red-900 w-full rounded-full font-black">
                    {{$deleteForm['submitLabel']}}
                </x-form.submit>
            </form>

            <hr class="border-primary col-span-2">

            <div class="text-center lg:text-start text-4xl xl:text-4xl w-full font-medium">
                Cambia lingua
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
                <script>console.log(`{{App::currentLocale()}}`);</script>
            </div>

        </div>
    </main>

@endsection
