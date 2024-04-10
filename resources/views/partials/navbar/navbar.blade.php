<nav x-data="{showMenu:false}">
    <div class="z-30 fixed top-0 w-full h-20 p-2 lg:pr-20">
        <div class="w-full h-full rounded-full bg-white">
            <div class="h-full w-full flex items-center justify-between bg-primary/90 px-5 rounded-full">
                <a href="/">{{svg('other-logo','h-6 w-6')}}</a>
                <div
                    class="w-1/5 rounded-full p-2 bg-white flex items-center justify-center gap-2 ">{{svg('search','h-6 w-6')}}
                    cerca
                </div>
                <div @click="showMenu=!showMenu" class="lg:hidden cursor-pointer">{{svg('menu-toogle','h-6 w-6')}}</div>
            </div>
        </div>
    </div>
    <div x-show="showMenu" class="lg:hidden absolute inset-0 w-full h-full bg-white">
        <div class="w-full "></div>
forse meglio farlo w-20 a dx che tutto? poi dove metti il pulsante messaggi?
    </div>
</nav>

<div class="h-20 "></div>
