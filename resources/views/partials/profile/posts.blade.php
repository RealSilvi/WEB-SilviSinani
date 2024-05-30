@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var boolean $ownership
     */
    $user =  $user ?? auth()->user();
@endphp

<div x-data="posts({userId: {{$user->id}}, profileId: {{$profile->id}}, authProfileId: {{$authProfile->id}}})"
     class="flex flex-col gap-10">

    <template x-if="postPreviews.length == 0">
        <div class="mt-10 lg:mt-20 w-full h-full flex items-center justify-center text-center text-2xl font-medium ">
            <div class="flex flex-col lg:flex-row items-center justify-center gap-5">
                <span>
                    Non sono stati trovati post
                </span>
                <a href="{{route('dashboard',['profile'=>$authProfile->nickname])}}">
                    {{svg('other-logo','h-8 w-8 lg:h-10 lg:w-10')}}
                </a>
            </div>
        </div>

    </template>

    <template x-for="post in postPreviews">
        <div class="w-full">

        </div>
    </template>

    <div class="flex flex-col gap-3 lg:gap-5 p-5 lg:px-20 lg:py-10 bg-primary/10 rounded-xl">
        <div class="flex flex-row gap-2 items-center lg:gap-5 lg:text-xl italic">
            <img
                :src=`https://images.unsplash.com/photo-1591154669695-5f2a8d20c089?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8dXJsfGVufDB8fDB8fHww`
                alt="profile_image"
                class="h-7 w-7 lg:h-14 lg:w-14 rounded-full object-cover" />
            @Lorem
        </div>

        <div class="line-clamp-2 lg:line-clamp-3 text-pretty text-sm lg:text-lg lg:px-5">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid dolor ducimus fugit illum inventore nulla
            omnis quibusdam. Eligendi esse itaque, nemo nesciunt nulla perspiciatis quibusdam quod repellendus rerum
            sequi voluptatem.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid dolor ducimus fugit illum inventore nulla
            omnis quibusdam. Eligendi esse itaque, nemo nesciunt nulla perspiciatis quibusdam quod repellendus rerum
            sequi voluptatem.
        </div>
        <div>
            <img
                :src=`https://images.unsplash.com/photo-1591154669695-5f2a8d20c089?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8dXJsfGVufDB8fDB8fHww`
                alt="profile_image"
                class="rounded-xl w-full object-cover aspect-[1/1] lg:aspect-[2/1] lg:object-contain bg-primary/10" />
        </div>
        <div class="flex flex-row gap-2 lg:gap-3 items-center text-xs lg:text-base lg:px-5">
            <div @click=""
                 class="cursor-pointer">
                {{svg('like','h-5 w-5 lg:h-8 lg:w-8 text-primary')}}
            </div>
            <div>
                1245
            </div>
            <div></div>
            <div @click=""
                 class="cursor-pointer">
                {{svg('comments','h-5 w-5 lg:h-8 lg:w-8 text-primary')}}
            </div>
            <div>
                2354
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <div class="flex flex-col text-sm lg:text-base lg:gap-2 bg-primary/10 rounded-3xl px-4 lg:px-5 py-1 lg:py-3">
                <div class="flex flex-row items-center lg:gap-3 font-medium">
                    <img
                        :src=`https://images.unsplash.com/photo-1591154669695-5f2a8d20c089?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8dXJsfGVufDB8fDB8fHww`
                        alt="profile_image"
                        class="h-7 w-7 lg:h-10 lg:w-10 rounded-full object-cover hidden lg:block" />
                    @Lorem
                </div>

                <div class="flex justify-between  lg:gap-10 ">
                    <div class="line-clamp-2 text-sm lg:text-base">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur cupiditate, earum eius esse ex iure iusto labore laudantium maiores natus neque non praesentium reprehenderit rerum totam ullam veritatis vero voluptatem!
                    </div>
                    <div @click=""
                         class="cursor-pointer flex justify-end">
                        {{svg('like','h-5 w-5 lg:h-8 lg:w-8 text-primary')}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col text-sm lg:text-base lg:gap-2 bg-primary/10 rounded-3xl px-4 lg:px-5 py-1 lg:py-3">
                <div class="flex flex-row items-center lg:gap-3 font-medium">
                    <img
                        :src=`https://images.unsplash.com/photo-1591154669695-5f2a8d20c089?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8dXJsfGVufDB8fDB8fHww`
                        alt="profile_image"
                        class="h-7 w-7 lg:h-10 lg:w-10 rounded-full object-cover hidden lg:block" />
                    @Lorem
                </div>

                <div class="flex justify-between  lg:gap-10 ">
                    <div class="line-clamp-2 text-sm lg:text-base">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur cupiditate, earum eius esse ex iure iusto labore laudantium maiores natus neque non praesentium reprehenderit rerum totam ullam veritatis vero voluptatem!
                    </div>
                    <div @click=""
                         class="cursor-pointer flex justify-end">
                        {{svg('like','h-5 w-5 lg:h-8 lg:w-8 text-primary')}}
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
