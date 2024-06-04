@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Profile $profile
     * @var \App\Models\Profile $authProfile
     */
    $user =  $user ?? auth()->user();
@endphp
<div
    x-data="{showInput:false}"
    class="flex flex-col">
    <div class="flex flex-row gap-2 lg:gap-3 items-center text-xs lg:text-base lg:px-5">
        <div x-data="postLike({
            userId: {{$user->id}},
            profileId: {{$authProfile->id}},
        })"
             class="flex flex-row gap-2 lg:gap-3 items-center text-xs lg:text-base">
            <div @click="post.doYouLike ? unlikePost(post.id) : likePost(post.id);"
                 :class="post.doYouLike ? 'text-black' : 'text-primary'"
                 class="cursor-pointer rounded-full">
                {{svg('like','h-5 w-5 lg:h-8 lg:w-8')}}
            </div>
            <div x-text="post.likesCount"></div>
        </div>

        <div></div>

        <div @click="showInput=!showInput"
             class="cursor-pointer">
            {{svg('comments','h-5 w-5 lg:h-8 lg:w-8 text-primary')}}
        </div>
        <div x-text="post.commentsCount ?? '0'"></div>
    </div>
    <div
        x-show="showInput"
        x-collapse
        x-data="comment({
                userId: {{$user->id}},
                profileId: {{$authProfile->id}},
        })"
    >
        <form
            @submit.prevent="createComment($event,post.id)"
            @create-comment.window="showInput=false"
            class="relative overflow-hidden mt-5"
        >
            <x-form.group name="body" class="w-full">
                <x-form.label sr-only>
                    body
                </x-form.label>
                <x-form.textarea required rows="1"
                                 placeholder="Write your comment"
                                 class="placeholder-primary placeholder:font-light text-sm xl:text-lg !border py-2 pr-12 pl-2 rounded-xl flex-shrink" />
            </x-form.group>
            <x-form.submit class="absolute right-2 top-0 bg-transparent rounded-full !py-2">
                {{svg('send','h-5 w-5 lg:h-6 lg:w-6 text-primary')}}
            </x-form.submit>
        </form>
    </div>
</div>

