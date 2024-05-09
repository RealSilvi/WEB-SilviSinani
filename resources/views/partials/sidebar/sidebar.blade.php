@php
    /**
     * @var \App\Models\Profile $profile
     * @var \App\Models\User $user
     */
    $user= $user ?? auth()->user();
    $profile= $profile ?? $user->getDefaultProfile();
@endphp
<aside x-data="sidebar({ userId: {{auth()->id()}} })"
       class="fixed z-40 w-screen bottom-0 right-0 lg:top-0 lg:h-full lg:w-20 h-15 p-2 lg:py-0">
    <div class="w-full h-full bg-white rounded-full lg:flex lg:flex-col">
        <div class="hidden lg:block w-full pt-2 h-22">
            <div class="bg-primary/90 rounded-2xl w-full h-full">
                <a href="#" class="w-full h-full flex  items-center justify-center">
                    {{svg('conversations','lg:h-7 lg:w-7')}}
                </a>
            </div>
        </div>
        <div class=" h-full w-full lg:bg-white  lg:py-2 rounded-full">
            <div
                class="w-full h-full rounded-full bg-primary/90 flex flex-row lg:flex-col items-center justify-around px-5 lg:px-0 lg:py-20">
                <template x-for="profileLink in profileLinks">
                    <a :href="profileLink.href"
                       :class="window.location.pathname.includes(profileLink.nickname) ? 'p-1 border-2 border-white rounded-full': 'border-0 p-0'">
                        <img :src="profileLink.src" :alt="profileLink.alt"
                             class="h-7 w-7 lg:h-10 lg:w-10 rounded-full object-cover" />
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
                <form action="{{ route('logout') }}" method="POST" class="w-full h-full">
                    @csrf
                    <button class="w-full h-full flex items-center justify-center">
                        {{svg('logout',' h-6 w-6 lg:h-7 lg:w-7')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

