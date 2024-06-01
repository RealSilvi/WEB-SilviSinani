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
            <div class="flex flex-col gap-3 lg:gap-5 p-5 lg:px-20 lg:py-10 bg-primary/10 rounded-xl">
                <a :href="post.profileLink" class="flex flex-row gap-2 items-center lg:gap-5 lg:text-xl italic">
                    <img
                        alt="profile_image"
                        :src="post.profile.mainImage"
                        class="h-7 w-7 lg:h-14 lg:w-14 rounded-full object-cover" />
                    <div x-text="`@${post.profile.nickname}`"></div>
                </a>

                <div
                    x-text="post.description"
                    class="line-clamp-2 lg:line-clamp-3 text-pretty text-sm lg:text-lg lg:px-5">
                </div>
                <template x-if="post.image">
                    <img
                        :src="post.image"
                        alt="post_image"
                        class="rounded-xl w-full object-cover aspect-[1/1] lg:aspect-[2/1] lg:object-contain bg-primary/10" />
                </template>
                <div class="flex flex-row gap-2 lg:gap-3 items-center text-xs lg:text-base lg:px-5">
                    <div @click="post.doYouLike ? destroyPostLike(post.id) : createPostLike(post.id)"
                         :class="post.doYouLike ? 'text-black' : 'text-primary'"
                         class="cursor-pointer rounded-full">
                        {{svg('like','h-5 w-5 lg:h-8 lg:w-8')}}
                    </div>
                    <div x-text="post.likesCount ?? '0'"></div>
                    <div></div>
                    <div @click=""
                         class="cursor-pointer">
                        {{svg('comments','h-5 w-5 lg:h-8 lg:w-8 text-primary')}}
                    </div>
                    <div x-text="post.commentsCount ?? '0'"></div>
                </div>

                <div class="flex flex-col gap-2">
                    <template x-for="comment in post.commentPreviews">
                        <div
                            class="flex flex-col text-sm lg:text-base lg:gap-2 bg-primary/10 rounded-3xl px-4 lg:px-5 py-1 lg:py-3">
                            <a :href="comment.profileLink"  class="flex flex-row items-center lg:gap-3 font-medium">
                                <img
                                    :src="comment.profile.mainImage"
                                    alt="profile_image"
                                    class="h-7 w-7 lg:h-10 lg:w-10 rounded-full object-cover hidden lg:block" />
                                <div x-text="`@${comment.profile.nickname}`"></div>
                            </a>

                            <div class="flex justify-between lg:gap-10 ">
                                <div
                                    x-text="comment.body"
                                    class="line-clamp-2 text-sm lg:text-base">

                                </div>

                                <div class="flex flex-row gap-2 lg:gap-3 items-center text-xs lg:text-base lg:px-5">
                                    <div @click="comment.doYouLike ? deleteCommentLike(post.id,comment.id) : createCommentLike(post.id,comment.id)"
                                         :class="comment.doYouLike ? 'text-black' : 'text-primary'"
                                         class="cursor-pointer flex justify-end">
                                        {{svg('like','h-5 w-5 lg:h-8 lg:w-8')}}
                                    </div>
                                    <div x-text="comment.likesCount ?? '0'"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>
