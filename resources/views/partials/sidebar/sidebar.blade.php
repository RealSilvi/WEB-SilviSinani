<aside
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
            <div class="w-full h-full rounded-full bg-primary/90 flex flex-row lg:flex-col items-center justify-between px-5 lg:px-0 lg:py-20">
                <a href="#">
                    <x-image class="h-7 w-7 lg:h-10 lg:w-10 rounded-full" filter="user 01"></x-image>
                </a>
                <a href="#">
                    <x-image class="h-7 w-7 lg:h-10 lg:w-10  rounded-full" filter="user 02"></x-image>
                </a>
                <a href="#">
                    <x-image class="h-7 w-7 lg:h-10 lg:w-10  rounded-full" filter="user 03"></x-image>
                </a>
                <a href="#">
                    <x-image class="h-7 w-7 lg:h-10 lg:w-10  rounded-full" filter="user 04"></x-image>
                </a>
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

