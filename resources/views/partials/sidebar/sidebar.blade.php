@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var array{ id:string, method:string, submitLabel:string, url:string, action:string} $logoutForm
     */

    $user = $user ?? auth()->user();
    $authProfile = $authProfile ?? $user->getDefaultProfile();
    $profile = $profile ?? $authProfile;

    $logoutForm = [
        'id' => 'logout',
        'method' => 'POST',
        'action' => route('logout'),
        'submitLabel' =>   __('Logout'),
    ];
@endphp

<aside class="fixed z-40 w-full bottom-0 right-0 lg:top-0 lg:h-full lg:w-20 h-15 p-2 lg:py-0"
       x-data="sidebar({
            userId: {{$user->id}},
       })">
    <div class="w-full h-full bg-white rounded-full lg:flex lg:flex-col">

        <div class="hidden lg:block w-full pt-2 h-22">
            <div class="bg-primary/90 rounded-2xl w-full h-full relative flex items-end justify-end py-1 px-2">
                <div>
                    {{$authProfile->news_count}}
                </div>
                <a href="{{route('news',['profile'=>$authProfile->nickname])}}"
                   class=" absolute inset-0 w-full h-full flex items-center justify-center">
                    {{svg('heart','lg:h-7 lg:w-7')}}
                </a>
            </div>
        </div>

        <div class="h-full w-full lg:bg-white  lg:py-2 rounded-full">
            <div
                class="w-full h-full rounded-full bg-primary/90 flex flex-row lg:flex-col items-center justify-around px-5 lg:px-0 lg:py-20">
                <template x-for="profileLink in profileLinks">
                    <a :href="profileLink.href"
                       :class="profileLink.currentActive ? 'p-1 border-2 border-white rounded-full': 'border-0 p-0'">
                        <img class="h-7 w-7 lg:h-10 lg:w-10 rounded-full object-cover"
                             :src="profileLink.src"
                             :alt="profileLink.alt" />
                    </a>
                </template>
                <template x-if="canAddProfile">
                    <a href="{{route('createNewProfile')}}">
                        {{svg('add-profile','h-6 w-6 lg:h-7 lg:w-7')}}
                    </a>
                </template>
            </div>
        </div>

        <div class="hidden lg:block w-full pb-2 h-22">
            <div class="bg-primary/90 rounded-2xl w-full h-full">
                <form class="w-full h-full"
                      action="{{$logoutForm['action']}}"
                      method="{{$logoutForm['method']}}">
                    @csrf
                    <button class="w-full h-full flex items-center justify-center">
                        {{svg('logout',' h-6 w-6 lg:h-7 lg:w-7')}}
                    </button>
                </form>
            </div>
        </div>

    </div>
</aside>

