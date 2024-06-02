@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     * @var boolean $ownership
     */
    $user =  $user ?? auth()->user();
@endphp

<div
    x-data="posts({
              userId: {{$user->id}},
              profileId: {{$profile->id}},
              authProfileId: {{$authProfile->id}}
            })"
    class="flex flex-col gap-10">

    <template x-for="post in postPreviews">
        <div class="w-full">
            <div class="flex flex-col gap-3 lg:gap-5 p-5 lg:px-20 lg:py-10 bg-primary/10 rounded-xl">
                {{--Divided in partials just for clenner code.--}}

                @include('partials.profile.post-preview.post-details')

                @include('partials.profile.post-preview.interact')

                <div class="flex flex-col gap-2">
                    <template x-for="comment in post.commentPreviews">
                        @include('partials.profile.post-preview.comment')
                    </template>
                </div>

            </div>
        </div>
    </template>

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
</div>
