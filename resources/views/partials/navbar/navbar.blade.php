<nav x-cloak
     x-data="{showMenu:false}"
     x-effect="document.body.style.overflow = showMenu ? 'hidden' : ''">
    <div class="z-30 fixed inset-0 w-full h-20 p-2 lg:pr-22">
        <div class="w-full h-full rounded-full bg-white">
            <div class="h-full w-full flex items-center justify-between bg-primary/90 px-5 lg:px-10 rounded-full">
                <a href="{{route('home')}}">{{svg('other-logo','h-6 w-6 lg:h-7 lg:w-7')}}</a>
                <div
                        class="w-3/5 lg:w-1/4 rounded-full p-2 bg-white flex items-center justify-center gap-2 ">{{svg('search','h-6 w-6')}}
                    cerca
                </div>
                <a href="#" class="hidden lg:block">
                    {{svg('settings','lg:h-7 lg:w-7')}}
                </a>
                <div @click="showMenu=!showMenu" x-transition.duration.300ms class="lg:hidden cursor-pointer">
                    <div x-show="!showMenu" >
                        {{svg('menu-toogle','h-7 w-7')}}
                    </div>
                    <div x-show="showMenu" >
                        {{svg('close','h-7 w-7')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="showMenu" class="lg:hidden fixed inset-0 w-full h-full bg-black/50 pb-17 pt-22 px-4 ">
        <div
            class="w-full h-full flex justify-end overflow-hidden">
            <div
                x-show="showMenu"
                x-transition:enter-start="translate-x-full"
                x-transition:leave-end="translate-x-full"
                class="w-14 h-full flex flex-col items-center justify-around transition-all ease-in-out duration-300">
                <div x-on:click.outside="showMenu=false" class="w-full aspect-[1/1] bg-primary/90 rounded-xl">
                    <a href="#" class="w-full h-full flex items-center justify-center">
                        {{svg('conversations','h-6 w-6')}}
                    </a>
                </div>
                <div x-on:click.outside="showMenu=false" class="w-full aspect-[1/1] bg-primary/90 rounded-xl">
                    <a href="#" class="w-full h-full flex items-center justify-center">
                        {{svg('settings','h-6 w-6')}}
                    </a>
                </div>
                <div x-on:click.outside="showMenu=false" class="w-full aspect-[1/1] bg-primary/90 rounded-xl">
                    <form action="{{ route('logout') }}" method="POST" class="w-full h-full">
                        @csrf
                        <button class="w-full h-full flex items-center justify-center">
                            {{svg('logout',' h-6 w-6')}}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
