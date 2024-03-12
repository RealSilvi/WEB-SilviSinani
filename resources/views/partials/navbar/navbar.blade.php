<div x-data="{showMenu:false}" class="fixed w-full bg-gray-100 h-15 flex items-center justify-between px-5">
    <div>{{svg('conversations','h-6 w-6')}}</div>
    <div class="w-1/5 rounded-full p-2 bg-white flex items-center justify-center gap-2 ">{{svg('search','h-6 w-6')}} cerca</div>
    <div>{{svg('menu-toogle','h-6 w-6')}}</div>

    <div x-show="showMenu" @click="showMenu=false" class="absolute inset-0 h-screen w-full bg-black/25">
        <div class="flex justify-end">
            <div @click="showMenu=true" class="bg-gray-100 w-1/2 h-screen relative px-10 py-20">
                <div>
                    <x-navbar></x-navbar>
                </div>
                <button @click="showMenu=false" class="absolute top-5 right-5">
                    x
                </button>
            </div>
        </div>
    </div>
</div>

<div class="h-15 print:hidden"></div>
